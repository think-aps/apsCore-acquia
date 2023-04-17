<?php

  namespace Drupal\aps_theo_embed_field\Form;

  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;

  /**
   * Configure theo_embed_field settings for this site.
   */
  class SettingsForm extends ConfigFormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
      return 'theo_embed_field_admin_settings';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
      return ['aps_theo_embed_field.settings'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
      $config = $this->config('aps_theo_embed_field.settings');

      $form['license'] = [
        '#type' => 'textarea',
        '#title' => $this->t('THEOplayer License'),
        '#description' => $this->t('Enter your THEOplayer portal license'),
        '#default_value' => $config->get('theoplayer_default_licence'),
      ];
      return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $this->config('aps_theo_embed_field.settings')
        ->set('theoplayer_default_licence', $form_state->getValue('license'))
        ->save();

      parent::submitForm($form, $form_state);
    }

  }
