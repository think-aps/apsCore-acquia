<?php

namespace Drupal\aps_addevent\Plugin\Field\FieldWidget;

use Drupal;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'ApsAddEventDefaultWidget' widget.
 *
 * @FieldWidget(
 *   id = "ApsAddEventDefaultWidget",
 *   label = @Translation("Dropdown button"),
 *   field_types = {
 *     "ApsAddEvent"
 *   }
 * )
 */
class ApsAddEventDefaultWidget extends WidgetBase {

  /**
   * Define the form for the field type.
   * 
   * Inside this method we can define the form used to edit the field type.
   * 
   * Here there is a list of allowed element types: https://goo.gl/XVd4tA
   */
  public function formElement(
    FieldItemListInterface $items,
    $delta, 
    Array $element, 
    Array &$form, 
    FormStateInterface $formState
  ) {

    // Street

    $element['event_id'] = [
      '#type' => 'textfield',
      '#title' => t('AddEvent.com Unique Key'),

      // Set here the current value for this field, or a default value (or 
      // null) if there is no a value
      '#default_value' => isset($items[$delta]->event_id) ? 
          $items[$delta]->event_id : null,

      '#empty_value' => '',
      '#placeholder' => t('AddEvent.com Unique Key'),
    ];

    return $element;
  }

} // class