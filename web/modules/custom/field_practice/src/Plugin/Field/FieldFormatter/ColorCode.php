<?php

namespace Drupal\field_practice\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'snippets_default' formatter.
 *
 * @FieldFormatter(
 *   id = "color_code",
 *   label = @Translation("Color Code"),
 *   field_types = {
 *     "RGB_color"
 *   }
 * )
 */
class ColorCode extends FormatterBase {

  /**
   * {@inheritDoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    // FieldConfig::load()
    // dd($this->getFieldSetting('widget_type'));
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#markup' => 'The selected color is ' . $item->color,
      ];
    }

    return $elements;
  }

}
