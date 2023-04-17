<?php

namespace Drupal\aps_addevent\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'ApsAddEventDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "ApsAddEventDefaultFormatter",
 *   label = @Translation("Dropdown button"),
 *   field_types = {
 *     "ApsAddEvent"
 *   }
 * )
 */
class ApsAddEventDefaultFormatter extends FormatterBase {

  /**
   * Define how the field type is showed.
   *
   * Inside this method we can customize how the field is displayed inside
   * pages.
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $elements = [];
    $options = [];
    foreach ($items as $delta => $item) {
      $item_output = '';
      $types = array(
        'apple' => t('Apple'),
        'google' => t('Google'),
        'office365' => t('Outlook 365'),
        'outlook' => t('Outlook'),
        //'outlookcom' => t('Outlook.com'),
        'yahoo' => t('Yahoo'),
      );

      foreach ($item as $column => $value) {
        if ($column == 'event_id') {
            $item_output .= '<span><span class="addevent-button"><a>' . t('Add to Calendar') . '</a></span>';
            $item_output .= '<ul class="list-group list-group-flush">';
            foreach ($types as $type => $label) {
              $url = Url::fromUri("https://www.addevent.com/event/" . $value->getvalue() . '+' . $type);
              $project_link = Link::fromTextAndUrl($label, $url);
              $project_link = $project_link->toRenderable();
              $project_link['#attributes'] = array('class' => array('addevent', $type), 'target' => '_blank');

              $item_output .= '<li class="list-group-item">' . \Drupal::service('renderer')->render($project_link) . '</li>';
            }
            $item_output .= '</ul></span>';
        }
      }

      $elements[$delta]['field_addevent_row']['content'] = array(
        '#markup' => $item_output,
      );
    }

    return $elements;
  }

} // class
