<?php


	namespace Drupal\aps_graph_compare\Form;

  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;


	class APSWebformComparisonSettings extends ConfigFormBase {

    /**
     * @inheritDoc
     */
    public function getFormId() {
      return 'aps_graph_compare_settings_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
      return [
        'aps_graph_compare.settings',
      ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
      $config = $this->config('aps_graph_compare.settings');

      // Create a looping solution for the number of items to infinity
      if (empty($form_state->get('field_count'))) {
        $field_count = (!empty($config->get('comparison_keys')))? count($config->get('comparison_keys')) : 1;
        $form_state->set('field_count', $field_count);
      }

      $options = array();
      $webforms = \Drupal::entityTypeManager()->getStorage('webform')->loadMultiple();
      foreach ($webforms as $webform_name => $webform) {
        $options[$webform_name] = $webform->label();
      }

      $form['comparison_keys'] = array(
        '#type' => 'container',
        '#attributes' => array(
          'id' => 'configuration-fieldset-wrapper'
        ),
      );

      // Create the looping array for each row
      for ($i = 0; $i < $form_state->get('field_count'); $i++) {
        $x = $i+1;

        $form['comparison_keys'][$i] = array(
          '#type' => 'fieldset',
          '#title' => $this->t("Group {$x}"),
          '#collapsible' => TRUE,
        );

        $form['comparison_keys'][$i]["group-{$i}"] = array(
          '#type' => 'table',
          '#title' => $this->t("Group {$x}"),
          '#header' => array('Primary', 'Secondary'),
          '#attributes' => array(
            'id' => "comparison-group-{$i}"
          )
        );

        $form['comparison_keys'][$i]["group-{$i}"]['rows']['primary'] = array(
          '#type' => 'select',
          '#default_value' => $config->get('comparison_keys')[$i]['primary'],
          '#options' => $options,
          '#required' => TRUE,
        );
        $form['comparison_keys'][$i]["group-{$i}"]['rows']['secondary'] = array(
          '#type' => 'select',
          '#default_value' => $config->get('comparison_keys')[$i]['secondary'],
          '#options' => $options,
          '#required' => TRUE,
        );

        $form['comparison_keys'][$i]['table'] = aps_graph_compare_comparison_group_table($i);
      }
      $form['field_count'] = array(
        '#type' => 'hidden',
        '#value' => $form_state->get('field_count'),
      );

      $form['comparison_keys']['actions']['add_key'] = array(
        '#type' => 'submit',
        '#value' => t('Add new group'),
        '#submit' => array('::addOne'),
        '#ajax' => array(
          'callback' => '::addOneCallback',
          'wrapper'  => 'configuration-fieldset-wrapper',
        ),
      );

      return parent::buildForm($form, $form_state);
    }

    public function addOneCallback(array&$form, FormStateInterface $form_state) {
      return $form['comparison_keys'];
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
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $config = $this->config('aps_graph_compare.settings');
      $values = $form_state->getValues();

      $comparison_keys = array();
      foreach ($values as $row => $value) {
        // Ignore it if the values are empty
        if (strpos($row, 'group-') !== FALSE) {
          if (!(empty($value['rows']['primary']) && empty($value['rows']['secondary']))) {
            $comparison_keys[] = array(
              'primary' => $value['rows']['primary'],
              'secondary' => $value['rows']['secondary'],
            );
          }
        }
      }

      $config->set('comparison_keys', $comparison_keys);
      $config->save();

      // We have to clear the route cache or it'll crash
      \Drupal::service("router.builder")->rebuild();

      parent::submitForm($form, $form_state);
    }
	}
