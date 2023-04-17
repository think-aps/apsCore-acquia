<?php

namespace Drupal\aps_addevent\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface as StorageDefinition;

/**
 * Plugin implementation of the 'ApsAddEvent' field type.
 *
 * @FieldType(
 *   id = "ApsAddEvent",
 *   label = @Translation("ApsAddEvent"),
 *   description = @Translation("Stores an event id."),
 *   category = @Translation("Custom"),
 *   default_widget = "ApsAddEventDefaultWidget",
 *   default_formatter = "ApsAddEventDefaultFormatter"
 * )
 */
class ApsAddEvent extends FieldItemBase {

  /**
   * Field type properties definition.
   * 
   * Inside this method we defines all the fields (properties) that our 
   * custom field type will have.
   * 
   * Here there is a list of allowed property types: https://goo.gl/sIBBgO
   */
  public static function propertyDefinitions(StorageDefinition $storage) {

    $properties = [];

    $properties['event_id'] = DataDefinition::create('string')
      ->setLabel(t('Event Id'));

    return $properties;
  }

  /**
   * Field type schema definition.
   * 
   * Inside this method we defines the database schema used to store data for 
   * our field type.
   * 
   * Here there is a list of allowed column types: https://goo.gl/YY3G7s
   */
  public static function schema(StorageDefinition $storage) {

    $columns = [];
    $columns['event_id'] = [
      'type' => 'char',
      'length' => 255,
    ];

    return [
      'columns' => $columns,
      'indexes' => [],
    ];
  }

  /**
   * Define when the field type is empty. 
   * 
   * This method is important and used internally by Drupal. Take a moment
   * to define when the field fype must be considered empty.
   */
  public function isEmpty() {

    $isEmpty = empty($this->get('event_id')->getValue());

    return $isEmpty;
  }

} // class
