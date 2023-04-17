<?php

  namespace Drupal\aps_flowplayer\Plugin\Field\FieldFormatter;

  use Drupal\Core\Field\FieldItemListInterface;
  use Drupal\Core\Field\FormatterBase;
  use Drupal\Core\Form\FormStateInterface;

  /**
   * Plugin implementation of the 'aps_flowplayer' formatter.
   *
   * @FieldFormatter(
   *   id = "aps_flowplayer",
   *   label = @Translation("Flowplayer Embed"),
   *   field_types = {
   *     "aps_flowplayer"
   *   }
   * )
   */
  class FlowplayerFieldFormatter extends FormatterBase {

    /**
     * {@inheritdoc}
     */
    public static function defaultSettings() {
      return [
          'flowplayer_autoplay' => false,
          'flowplayer_controls' => 0,
          'flowplayer_live' => 0,
          'flowplayer_color' => 0,
          'flowplayer_color_value' => '#ffffff',
        ] + parent::defaultSettings();
    }

    /**
     * {@inheritdoc}
     */
    public function settingsForm(array $form, FormStateInterface $form_state) {
      $elements = parent::settingsForm($form, $form_state);

      $elements['flowplayer_autoplay'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Autoplay video'),
        '#default_value' => $this->getSetting('flowplayer_autoplay'),
        '#description' => $this->t('Automatically play the video on load'),
      ];
      $elements['flowplayer_live'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Live Mode'),
        '#default_value' => $this->getSetting('flowplayer_live'),
        '#description' => $this->t("Display video as live, this will hide the speed controls and scrub bar"),
      ];
      $elements['flowplayer_controls'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Hide the entire control bar'),
        '#default_value' => $this->getSetting('flowplayer_controls'),
        '#description' => $this->t('Remove the entire control bar, not advisable as this leaves the user with no control.'),
      ];
      $elements['flowplayer_color'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Set colour on video play button'),
        '#default_value' => $this->getSetting('flowplayer_color'),
      ];
      $elements['flowplayer_color_value'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Colour'),
        '#size' => 7,
        '#maxlength' => 7,
        '#default_value' => $this->getSetting('flowplayer_color_value') ? $this->getSetting('flowplayer_color_value') : '#ffffff',
        '#description' => $this->t("CSS colours, include # for Hexadecimal colours"),
        '#states' => [
          'visible' => [
            ':input[name*="flowplayer_color"]' => ['checked' => TRUE],
          ],
        ],
      ];
      return $elements;
    }

    /**
     * {@inheritdoc}
     */
    public function settingsSummary() {
      $colour = $this->getSetting('flowplayer_color_value');
      $summary[] = $this->t('Flowplayer Embed (Autoplay: @autoplay, Live Mode: @live, Controls: @controls, Colour: @colour).', [
        '@autoplay' => $this->getSetting('flowplayer_autoplay') ? $this->t('On') : $this->t('Off'),
        '@live' => $this->getSetting('flowplayer_live') ? $this->t('Enabled') : $this->t('Disabled'),
        '@controls' => $this->getSetting('flowplayer_controls') ? $this->t('Hidden') : $this->t('Visible'),
        '@colour' => $this->getSetting('flowplayer_color_value') ? $colour : $this->t('Default'),
      ]);
      return $summary;
    }

    /**
     * {@inheritdoc}
     */
    public function viewElements(FieldItemListInterface $items, $langcode) {
      $elements = [];

      foreach ($items as $delta => $item) {
        $elements[$delta] = [
          '#theme' => 'aps_flowplayer',
          '#key' => $item->flowplayer_videokey,
          '#colour' => $this->getSetting('flowplayer_color_value'),
          '#autoplay' => $this->getSetting('flowplayer_autoplay'),
          '#controls' => $this->getSetting('flowplayer_controls'),
          '#live' => $this->getSetting('flowplayer_live'),
        ];
      }

      return $elements;
    }
  }
