<?php


  namespace Drupal\aps_campaignmonitor\Form;

  use Drupal;
  use Drupal\Core\Entity\EntityStorageException;
  use Drupal\Core\Form\FormStateInterface;
  use Drupal\Core\Form\ConfigFormBase;

  class FlagForm extends ConfigFormBase {

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
      return 'aps_campaignmonitor_flag_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state): array {
      $config = $this->config('aps_campaignmonitor.settings');

      $form['flag'] = array(
        '#type' => 'fieldset',
        '#title' => $this->t('Entity Registration'),
        '#collapsible' => FALSE,
        '#collapsed' => FALSE,
      );

      if (Drupal::service('module_handler')->moduleExists('flag')) {
        $flags = Drupal::service('flag')->getAllFlags();

        $options = array('_none' => $this->t('- None -'));
        foreach ($flags as $flag_name => $flag) {
          $options[$flag_name] = $flag->Label();
        }

        $form['flag']['subscriber_flag'] = array(
          '#title' => t('Flag'),
          '#type' => 'select',
          '#default_value' => $config->get('subscriber_flag'),
          '#options' => $options,
          '#description' => $this->t('Select the flag which will be the controller for generating lists within Campaign Monitor')
        );
      } else {
        $form['flag']['disabled'] = array(
          '#type' => 'item',
          '#markup' => $this->t('Flags module is disable, it must be active to use this controller'),
        );
      }

      return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {

    }

    /**
     * {@inheritdoc}
     *
     * @throws EntityStorageException
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $config = $this->config('aps_campaignmonitor.settings');

      // Check that they haven't changed the option to 'None'
      if ($flag = Drupal::service('flag')->getFlagById($form_state->getValue('subscriber_flag'))) {
        if ($flag instanceof Drupal\flag\Entity\Flag) {
          $entity_bundles = Drupal::service('entity_type.bundle.info')->getBundleInfo($flag->getFlaggableEntityTypeId());

          foreach ($entity_bundles as $bundle => $bundle_info) {
            aps_campaignmonitor_create_entity_field($flag->getFlaggableEntityTypeId(), $bundle);
          }

          $flagged_entities = array(
            'flag_id' => $flag->id(),
            'field_label' => $flag->label(),
          );

          $config->set('flagged_entities', array($flagged_entities));
        }
      }

      $config->set('subscriber_flag', $form_state->getValue('subscriber_flag'));
      $config->save();

      parent::submitForm($form, $form_state);
    }
  }
