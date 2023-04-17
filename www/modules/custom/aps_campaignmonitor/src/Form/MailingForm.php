<?php


  namespace Drupal\aps_campaignmonitor\Form;

  use Drupal\Core\Form\FormStateInterface;
  use Drupal\Core\Form\ConfigFormBase;

  class MailingForm extends ConfigFormBase {

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
      return 'aps_campaignmonitor_mailing_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state): array {
      $config = $this->config('aps_campaignmonitor.settings');

      $form['drupal'] = array(
        '#type' => 'fieldset',
        '#title' => $this->t('Drupal Fallback'),
        '#collapsible' => FALSE,
        '#collapsed' => FALSE,
      );
      $form['drupal']['fallback'] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Drupal Sender Fallback'),
        '#default_value' => $config->get('drupal_fallback'),
        '#description' => $this->t('<strong>Enable with caution</strong>, this checkbox to allow Drupal to send out any email that failed to be sent via Campaign Monitor, be aware that this will mean that emails could come from Drupal unbranded. Attempts will still be reported in the log messages regardless of this setting.'),
      );

      // Create a looping solution for the number of items to infinity
      if (empty($form_state->get('field_count'))) {
        $field_count = (!empty($config->get('mailing_keys')))? count($config->get('mailing_keys')) : 1;
        $form_state->set('field_count', $field_count);
      }

      $form['mailing_keys'] = array(
        '#type' => 'table',
        '#title' => $this->t('Mailing Keys'),
        '#header' => array('Transactional Email ID', 'Drupal Message Key', 'Search Method'),
        '#attributes' => array(
          'id' => 'configuration-fieldset-wrapper'
        ),
      );

      // Create the looping array for each row
      for ($i = 0; $i < $form_state->get('field_count'); $i++) {
        $disabled = $i === 0;

        $form['mailing_keys'][$i]['transactional_id'] = array(
          '#type' => 'textfield',
          '#default_value' => $config->get('mailing_keys')[$i]['transactional_id'],
        );
        $form['mailing_keys'][$i]['message_key'] = array(
          '#type' => 'textfield',
          '#default_value' => $config->get('mailing_keys')[$i]['message_key'],
          '#disabled' => $disabled,
        );
        $form['mailing_keys'][$i]['method'] = array(
          '#type' => 'select',
          '#options' => array(
            'equals' => $this->t('Equals'),
            'starts' => $this->t('Starts with'),
            'contains' => $this->t('Contains'),
            'ends' => $this->t('Ends with'),
          ),
          '#default_value' => $config->get('mailing_keys')[$i]['method'],
          '#disabled' => $disabled,
        );
      }
      $form['field_count'] = array(
        '#type' => 'hidden',
        '#value' => $form_state->get('field_count'),
      );

      $form['mailing_keys']['actions']['add_name'] = array(
        '#type' => 'submit',
        '#value' => t('Add Transactional Email'),
        '#submit' => array('::addOne'),
        '#ajax' => array(
          'callback' => '::addOneCallback',
          'wrapper'  => 'configuration-fieldset-wrapper',
        ),
      );

      return parent::buildForm($form, $form_state);
    }

    public function addOneCallback(array&$form, FormStateInterface $form_state) {
      return $form['mailing_keys'];
    }

    public function addOne(array&$form, FormStateInterface $form_state) {
      $field_count = $form_state->get('field_count');
      $field_count++;

      $form_state->set('field_count', $field_count);
      $form_state->setRebuild();
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
      $active_key = false;

      foreach ($form_state->getValue('mailing_keys') as $row => $mailing_key) {
        if (is_integer($row)) {
          if ((empty($mailing_key['transactional_id']) || empty($mailing_key['message_key'])) && !(empty($mailing_key['transactional_id']) && empty($mailing_key['message_key']))) {
            $form_state->setError($form['mailing_keys'][$row], t('Must enter a value for both Transactional ID and Message Key'));
          }

          if(!empty($mailing_key['transactional_id'])) {
            $active_key = true;
          }
        }
      }

      if (!$active_key) {
        $form_state->setError($form['drupal']['fallback'], t('Cannot disable Drupal fallback without at least 1 Transactional ID'));
      }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $config = $this->config('aps_campaignmonitor.settings');

      $mailing_keys = array();
      foreach ($form_state->getValue('mailing_keys') as $row => $mailing_key) {
        // Ignore it if the values are empty
        if (is_integer($row)) {
          if (!(empty($mailing_key['transactional_id']) && empty($mailing_key['message_key']))) {
            $mailing_keys[] = array(
              'transactional_id' => $mailing_key['transactional_id'],
              'message_key' => $mailing_key['message_key'],
              'method' => $mailing_key['method'],
            );
          }
        }
      }

      $config->set('drupal_fallback', $form_state->getValue('fallback'));
      $config->set('mailing_keys', $mailing_keys);
      $config->save();

      parent::submitForm($form, $form_state);
    }
  }
