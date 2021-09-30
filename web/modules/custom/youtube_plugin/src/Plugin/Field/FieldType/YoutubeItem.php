<?php

namespace Drupal\youtube_plugin\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the YouTube field type.
 *
 * @FieldType(
 *   id = "youtube_plugin",
 *   label = @Translation("Youtube video"),
 *   module = "youtube_plugin",
 *   description = @Translation("Output video from Youtube."),
 *   default_widget = "youtube_plugin",
 *   default_formatter = "youtube_plugin"
 * )
 */
class YoutubeItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'text',
          'size' => 'tiny',
          'not null' => FALSE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Youtube video URL'));

    return $properties;
  }

}
