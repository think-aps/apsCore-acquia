<?php

  namespace Drupal\aps_flowplayer\Plugin\Field\FieldType;

  use Drupal\Core\Field\FieldItemBase;
  use Drupal\Core\Field\FieldStorageDefinitionInterface;
  use Drupal\Core\StringTranslation\TranslatableMarkup;
  use Drupal\Core\TypedData\DataDefinition;

  /**
   * Plugin implementation of the 'aps_flowplayer' field type.
   *
   * @FieldType(
   *   id = "aps_flowplayer",
   *   label = @Translation("Flowplayer embed field"),
   *   description = @Translation("This field stores a Flowplayer Video Key in the Drupal database."),
   *   category = @Translation("Flowplayer"),
   *   default_widget = "aps_flowplayer",
   *   default_formatter = "aps_flowplayer"
   * )
   */
  class FlowplayerField extends FieldItemBase {

    /**
     * {@inheritdoc}
     */
    public static function defaultStorageSettings() {
      return [
          'max_length' => 255,
        ] + parent::defaultStorageSettings();
    }

    /**
     * {@inheritdoc}
     */
    public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
      // Prevent early t() calls by using the TranslatableMarkup.
      $properties['flowplayer_videokey'] = DataDefinition::create('string')
        ->setLabel(new TranslatableMarkup('Flowplayer Video Key'))
        ->setRequired(TRUE);

      return $properties;
    }

    /**
     * {@inheritdoc}
     */
    public static function schema(FieldStorageDefinitionInterface $field_definition) {
      return array(
        'columns' => array(
          'flowplayer_videokey' => array(
            'type' => 'varchar',
            'description' => 'Flowplayer Video Key',
            'length' => 256,
            'not null' => FALSE,
          ),
        ),
      );
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty() {
      $flowplayer_videokey = $this->get('flowplayer_videokey')->getValue();

      return $flowplayer_videokey === NULL;
    }

  }
