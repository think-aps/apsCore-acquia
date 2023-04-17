<?php

  namespace Drupal\aps_theo_embed_field\Plugin\Field\FieldFormatter;

  use Drupal\Core\Field\FieldItemListInterface;
  use Drupal\Core\Field\FormatterBase;
  use Drupal\Core\Form\FormStateInterface;

  /**
   * Plugin implementation of the 'theojs' formatter.
   *
   * @FieldFormatter(
   *   id = "theojs",
   *   label = @Translation("THEOplayer Embed"),
   *   field_types = {
   *     "theojs"
   *   }
   * )
   */
  class TheojsFieldFormatter extends FormatterBase {

    /**
     * {@inheritdoc}
     */
    public static function defaultSettings() {
      return [
          'theo_autoplay' => false,
          'theo_controls' => 0,
          'theo_live' => 0,
          'theo_color' => 0,
          'theo_color_value' => '#0093cb',
        ] + parent::defaultSettings();
    }

    /**
     * {@inheritdoc}
     */
    public function settingsForm(array $form, FormStateInterface $form_state) {
      $elements = parent::settingsForm($form, $form_state);

      $elements['theo_autoplay'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Autoplay video'),
        '#default_value' => $this->getSetting('theo_autoplay'),
        '#description' => $this->t('Automatically play the video on load'),
      ];
      $elements['theo_live'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Live Mode'),
        '#default_value' => $this->getSetting('theo_live'),
        '#description' => $this->t("Display video as live, this will hide the speed controls and scrub bar"),
      ];
      $elements['theo_controls'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Hide the entire control bar'),
        '#default_value' => $this->getSetting('theo_controls'),
        '#description' => $this->t('Remove the entire control bar, not advisable as this leaves the user with no control.'),
      ];
      $elements['theo_color'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Set colour on video play button'),
        '#default_value' => $this->getSetting('theo_color'),
      ];
      $elements['theo_color_value'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Colour'),
        '#size' => 7,
        '#maxlength' => 7,
        '#default_value' => $this->getSetting('theo_color_value') ? $this->getSetting('theo_color_value') : 'red',
        '#description' => $this->t("Css colours, Include # for Hexadecimal colours"),
        '#states' => [
          'visible' => [
            ':input[name*="theo_color"]' => ['checked' => TRUE],
          ],
        ],
      ];
      return $elements;
    }

    /**
     * {@inheritdoc}
     */
    public function settingsSummary() {
      $colour = $this->getSetting('theo_color_value');
      $summary[] = $this->t('THEOplayer Embed (Autoplay: @autoplay, Live Mode: @live, Controls: @controls, Colour: @colour).', [
        '@autoplay' => $this->getSetting('theo_autoplay') ? $this->t('On') : $this->t('Off'),
        '@live' => $this->getSetting('theo_live') ? $this->t('Enabled') : $this->t('Disabled'),
        '@controls' => $this->getSetting('theo_controls') ? $this->t('Hidden') : $this->t('Visible'),
        '@colour' => $this->getSetting('theo_color_value') ? $colour : $this->t('Default'),
      ]);
      return $summary;
    }

    /**
     * {@inheritdoc}
     */
    public function viewElements(FieldItemListInterface $items, $langcode) {
      $elements = [];

      $i=0;
      foreach ($items as $delta => $item) {
        $key = str_replace("_", "-", $items->getName()) . "-{$i}";
        $elements[$delta] = [
          '#theme' => 'theojs',
          '#library' => $item->theoplayer_videokey,
          '#thumbnail' => $item->theoplayer_thumbnail_url,
          '#license' => \Drupal::config('aps_theo_embed_field.settings')->get('theoplayer_default_licence'),
          '#colour' => $this->getSetting('theo_color_value'),
          '#autoplay' => $this->getSetting('theo_autoplay') == 1 ? 'true' : 'false',
          '#src' => $item->theoplayer_playlist_url,
          '#controls' => $this->getSetting('theo_controls'),
          '#live' => $this->getSetting('theo_live'),
          '#num' => $key,
          '#image' => $this->geturlfromfile($item->theoplayer_image),
        ];
        $i++;
      }

      return $elements;
    }

    /**
     * {@inheritdoc}
     */
    public function geturlfromfile($fid = '') {
      $path = '';

      if (($fid != 0) && !empty($fid)) {
        $file = \Drupal\file\Entity\File::load($fid);
        if ($file != NULL){
          $node = \Drupal::routeMatch()->getParameter('node');
          $nid = $node->id();
          $file->setPermanent();
          $file->save();
          $path = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
          $file_usage = \Drupal::service('file.usage');
          $file_usage->add($file, 'file', 'node', $nid);
        }
      }

      return $path;
    }
  }
