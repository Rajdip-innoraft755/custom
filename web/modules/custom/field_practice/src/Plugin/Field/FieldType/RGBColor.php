<?php

namespace Drupal\field_practice\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'RGBColor' field type.
 *
 * @FieldType(
 *   id = "RGB_color",
 *   label = @Translation("RGB Color field"),
 *   description = @Translation("This field stores RGB color in database."),
 *   default_widget = "hex_code",
 *   default_formatter = "text_with_color"
 * )
 */
class RGBColor extends FieldItemBase {

  /**
   * {@inheritDoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field) {
    return [
      'columns' => [
        'color' => [
          'type' => 'varchar',
          'not null' => TRUE,
          'length' => 255,
        ],
      ],
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function isEmpty() {
    $value = $this->get('color')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritDoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['color'] = DataDefinition::create('string')
      ->setLabel(t('Hex value'))
      ->setDescription(t('This indicates the hex value for the color.'));
    return $properties;
  }

}
