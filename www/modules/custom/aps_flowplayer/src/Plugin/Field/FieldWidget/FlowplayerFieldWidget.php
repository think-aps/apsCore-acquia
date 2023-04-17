<?php

  namespace Drupal\aps_flowplayer\Plugin\Field\FieldWidget;

  use Drupal\Core\Field\FieldItemListInterface;
  use Drupal\Core\Field\WidgetBase;
  use Drupal\Core\Form\FormStateInterface;

  /**
   * Plugin implementation of the 'Flowplayer' widget.
   *
   * @FieldWidget(
   *   id = "aps_flowplayer",
   *   label = @Translation("Flowplayer widget"),
   *   field_types = {
   *     "aps_flowplayer"
   *   }
   * )
   */
  class FlowplayerFieldWidget extends WidgetBase {

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

      return $elements;
    }

    /**
     * {@inheritdoc}
     */
    public function settingsSummary() {
      $summary = [];

      $summary[] = $this->t('Textfield size: @size', ['@size' => $this->getSetting('size')]);
      return $summary;
    }

    /**
     * {@inheritdoc}
     */
    public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

      $element['flowplayer_videokey'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Unique key'),
        '#default_value' => isset($items[$delta]->flowplayer_videokey) ? $items[$delta]->flowplayer_videokey : NULL,
        '#size' => $this->getSetting('size'),
        '#maxlength' => '8',
        '#description' => 'The unique code from the embed HTML, eg. //player.video.wowza.com/hosted/<strong>UNIQUE_KEY</strong>/wowza.js'
      );

      return $element;
    }
  }
