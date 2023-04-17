<?php

  namespace Drupal\aps_theo_embed_field\Plugin\Field\FieldWidget;

  use Drupal\Core\Field\FieldItemListInterface;
  use Drupal\Core\Field\WidgetBase;
  use Drupal\Core\Form\FormStateInterface;

  /**
   * Plugin implementation of the 'theo' widget.
   *
   * @FieldWidget(
   *   id = "theojs",
   *   label = @Translation("THEOplayer widget"),
   *   field_types = {
   *     "theojs"
   *   }
   * )
   */
  class TheoFieldWidget extends WidgetBase {

    /**
     * {@inheritdoc}
     */
    public static function defaultSettings() {
      return [
          'size' => 60,
          'placeholder' => '',
        ] + parent::defaultSettings();
    }

    /**
     * {@inheritdoc}
     */
    public function settingsForm(array $form, FormStateInterface $form_state) {
      $elements = [];

      $elements['size'] = [
        '#type' => 'number',
        '#title' => $this->t('Size of textfield'),
        '#default_value' => $this->getSetting('size'),
        '#required' => TRUE,
        '#min' => 1,
      ];
      $elements['placeholder'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Placeholder'),
        '#default_value' => $this->getSetting('placeholder'),
        '#description' => $this->t('Text that will be shown inside the field until a value is entered. This hint is usually a sample value or a brief description of the expected format.'),
      ];

      return $elements;
    }

    /**
     * {@inheritdoc}
     */
    public function settingsSummary() {
      $summary = [];

      $summary[] = $this->t('Textfield size: @size', ['@size' => $this->getSetting('size')]);
      if (!empty($this->getSetting('placeholder'))) {
        $summary[] = $this->t('Placeholder: @placeholder', ['@placeholder' => $this->getSetting('placeholder')]);
      }

      return $summary;
    }

    /**
     * {@inheritdoc}
     */
    public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

      $element += [
        '#type' => 'fieldset',
      ];

      $element['theoplayer_videokey'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Library Location'),
        '#default_value' => isset($items[$delta]->theoplayer_videokey) ? $items[$delta]->theoplayer_videokey : NULL,
        '#size' => $this->getSetting('size'),
        '#placeholder' => $this->getSetting('placeholder'),
        '#maxlength' => '255',
        '#required' => true,
        '#description' => 'e.g. https://cdn.myth.theoplayer.com/a1b2c3d4e5...'
      );

      $element['theoplayer_playlist_url'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Stream Address'),
        '#default_value' => isset($items[$delta]->theoplayer_playlist_url) ? $items[$delta]->theoplayer_playlist_url : NULL,
        '#size' => $this->getSetting('size'),
        '#placeholder' => $this->getSetting('placeholder'),
        '#maxlength' => '255',
        '#required' => true,
        '#description' => 'e.g. https://cdn3.wowza.com/1/a1b2c3d4e5/ABCDEF/hls/live/playlist.m3u8'
      );

      $element['theoplayer_thumbnail_url'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Poster Image URL'),
        '#default_value' => isset($items[$delta]->theoplayer_thumbnail_url) ? $items[$delta]->theoplayer_thumbnail_url : NULL,
        '#size' => $this->getSetting('size'),
        '#placeholder' => $this->getSetting('placeholder'),
        '#maxlength' => '255',
        '#description' => 'External URL will take precedence over internal thumbnail',
        '#required' => false
      );

      $element['theoplayer_image'] = array(
        '#type' => 'managed_file',
        '#title' => $this->t('Poster Image upload'),
        '#empty_value' => 0,
        '#default_value' => (isset($items[$delta]->theoplayer_image)) ? [$items[$delta]->theoplayer_image] : NULL,
        '#upload_location' => 'public://theoplayer',
        '#upload_validators' => array(
          'file_validate_extensions' => array('gif png jpg jpeg'),
          'file_validate_size' => array(25600000),
        ),
        '#required' => false,
      );

      $element['theoplayer_image_revision'] = array(
        '#type' => 'hidden',
        '#default_value' => isset($items[$delta]->theoplayer_image) ? $items[$delta]->theoplayer_image : null
      );

      return $element;
    }

    /**
     * {@inheritdoc}
     */
    public function massageFormValues(array $values, array $form, FormStateInterface $form_state)
    {
      foreach ($values as &$value) {
        if (count($value['theoplayer_image'])) {
          foreach ($value['theoplayer_image'] as $fid) {
            $value['theoplayer_image'] = $fid;
          }
        } else {
          $value['theoplayer_image'] = $value['theoplayer_image_revision'] !== '' ? $value['theoplayer_image_revision'] : '0';
        }

      }

      return $values;
    }

  }
