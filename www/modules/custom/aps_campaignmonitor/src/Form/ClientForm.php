<?php


  namespace Drupal\aps_campaignmonitor\Form;

  use Drupal;
  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;

  class ClientForm extends ConfigFormBase {

    /**
     * @inheritDoc
     */
    protected function getEditableConfigNames(): array {
      return [
        'aps_campaignmonitor.settings',
      ];
    }

    /**
     * @inheritDoc
     */
    public function getFormId(): string {
      return 'aps_campaignmonitor_client_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state): array {
      $config = $this->config('aps_campaignmonitor.settings');

      $form['api'] = array(
        '#type' => 'fieldset',
        '#title' => $this->t('Client Account'),
        '#collapsible' => FALSE,
        '#collapsed' => FALSE,
      );
      $form['api']['info'] = array(
        '#type' => 'item',
        '#markup' => $this->t('Within the primary aps account, navigate to \'Account Settings\', then \'API Keys\' in the sidebar menu'),
      );
      $form['api']['client_id'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Client ID'),
        '#required' => TRUE,
        '#default_value' => $config->get('client_id'),
      );
      $form['api']['api_key'] = array(
        '#type' => 'textarea',
        '#title' => $this->t('API Key'),
        '#rows' => 2,
        '#required' => TRUE,
        '#default_value' => $config->get('api_key'),
        '#description' => $this->t('The API key should be specific for the client you are connecting to, not the master account API key'),
      );

      if ($client_id = Drupal::config('aps_campaignmonitor.settings')->get('client_id')) {
        $response = aps_campaignmonitor_httpclient_request('GET', "clients/{$client_id}.json?pretty=true", null);
        if ($response && $response->getStatusCode() == 200) {
          $form['client_info'] = array(
            '#type' => 'table',
            '#title' => $this->t('Client Info'),
            '#header' => array('Key', 'Value'),
          );

          $contents = json_decode($response->getBody()->getContents());
          foreach ($contents->BasicDetails as $key => $value) {
            $form['client_info'][] = array(
              'key' => array('#type' => 'item', '#markup' => $this->t($key)),
              'value' => array('#type' => 'item', '#markup' => $this->t($value)),
            );
          }
        } else {
          Drupal::messenger()->addWarning(t('Could not connect to a Campaign Monitor account with these credentials.'));
        }
      }

      return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
      $client_id = $form_state->getValue('client_id');
      $api_key = trim($form_state->getValue('api_key'));

      if (strlen($client_id) !== 32) {
        $form_state->setErrorByName('client_id', t('Client ID must have exactly 32 characters'));
      }

      $is_valid = aps_campaignmonitor_validate_client($client_id, $api_key);

      if (!$is_valid) {
        $form_state->setError($form, t('Could not retrieve information from this Client, check your Client ID and API Key are correct.'));
      }

    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      // We have to save the API key straight away so that the HTTPClient function below can use it
      $client_id = $form_state->getValue('client_id');
      $api_key = trim($form_state->getValue('api_key'));

      $config = $this->config('aps_campaignmonitor.settings');
      $config->set('client_id', $client_id);
      $config->set('api_key', $api_key);
      $config->save();

      $lists = aps_campaignmonitor_get_lists();

      // Loop through all the lists and check against the All Users list
      foreach ($lists as $key => $list) {
        if ($list->Name === APS_CM_ALL_USER_LIST) {
          $list_id = $list->ListID;
          $config->set('all_users_list_id', $list_id);
          break;
        }
      }

      // Check if we found the list ID, if not, we need to create it, and add
      // all users to it.
      if (!isset($list_id)) {

        $list_id = aps_campaignmonitor_create_list();
        if (!$list_id) {
          Drupal::messenger()->addWarning('@list list could not be created.', array(
            '@list' => APS_CM_ALL_USER_LIST,
          ));
        }
        else {
          $config->set('all_users_list_id', $list_id);

          // Now add all the existing users
          $existing_users = Drupal\user\Entity\User::loadMultiple();
          $result = aps_campaignmonitor_bulk_subscribe_users_to_list($existing_users, $list_id);

          if ($result) {
            Drupal::messenger()->addMessage(t('\'@list\' list created within Client. @count existing users were added (it might take a few minutes to appear in Campaign Monitor).', array(
              '@list' => APS_CM_ALL_USER_LIST,
              '@count' => count($existing_users),
            )));
          } else {
            Drupal::messenger()->addWarning('Error while importing some/all the existing users to the list @list', array(
              '@list' => APS_CM_ALL_USER_LIST,
            ));
          }
        }
      }

      $config->save();

      parent::submitForm($form, $form_state);
    }
  }
