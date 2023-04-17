<?php

  namespace Drupal\aps_theo_embed_field\Plugin\Field\FieldType;

  use Drupal\Core\Field\FieldItemBase;
  use Drupal\Core\Field\FieldStorageDefinitionInterface;
  use Drupal\Core\StringTranslation\TranslatableMarkup;
  use Drupal\Core\TypedData\DataDefinition;

  /**
   * Plugin implementation of the 'theojs' field type.
   *
   * @FieldType(
   *   id = "theojs",
   *   label = @Translation("THEOplayer embed field"),
   *   description = @Translation("This field stores a Theo portal Video Key in the Drupal database."),
   *   category = @Translation("THEOplayer"),
   *   default_widget = "theojs",
   *   default_formatter = "theojs"
   * )
   */
  class TheoField extends FieldItemBase {

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
      $properties['theoplayer_videokey'] = DataDefinition::create('string')
        ->setLabel(new TranslatableMarkup('THEOplayer Video Key'))
        ->setRequired(TRUE);

      $properties['theoplayer_playlist_url'] = DataDefinition::create('string')
        ->setLabel(new TranslatableMarkup('THEOplayer Stream Address'))
        ->setRequired(TRUE);

      $properties['theoplayer_thumbnail_url'] = DataDefinition::create('string')
        ->setLabel(new TranslatableMarkup('THEOplayer Poster Image URL'))
        ->setRequired(TRUE)
        ->setDescription(t('Full poster image url'));

      $properties['theoplayer_image'] = DataDefinition::create('string')
        ->setLabel(new TranslatableMarkup('THEOplayer Poster Image'))
        ->setRequired(TRUE)
        ->setDescription(t('Full poster image'));

      return $properties;
    }

    /**
     * {@inheritdoc}
     */
    public static function schema(FieldStorageDefinitionInterface $field_definition) {
      return array(
        'columns' => array(
          'theoplayer_videokey' => array(
            'type' => 'varchar',
            'description' => 'THEOplayer Video Key',
            'length' => 256,
            'not null' => FALSE,
          ),
          'theoplayer_playlist_url' => array(
            'type' => 'varchar',
            'description' => 'THEOplayer Stream Address',
            'length' => 256,
            'not null' => FALSE,
          ),
          'theoplayer_thumbnail_url' => array(
            'type' => 'varchar',
            'description' => 'THEOplayer Poster Image URL',
            'length' => 256,
            'not null' => FALSE,
          ),
          'theoplayer_image' => array(
            'type' => 'int',
            'description' => 'THEOplayer Poster Image',
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
      $theoplayer_videokey = $this->get('theoplayer_videokey')->getValue();
      $theoplayer_playlist_url = $this->get('theoplayer_playlist_url')->getValue();

      return $theoplayer_videokey === NULL || $theoplayer_videokey === ''
        || $theoplayer_playlist_url === NULL || $theoplayer_playlist_url === '';
    }

  }
