<?php

namespace Drupal\field_practice\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'snippets_default' formatter.
 *
 * @FieldFormatter(
 *   id = "text_with_color",
 *   label = @Translation("Text with the selected colored background."),
 *   field_types = {
 *     "RGB_color"
 *   }
 * )
 */
class TextWithColor extends FormatterBase {

  /**
   * {@inheritDoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'style' => ['color:' . $item->color . ';font-size:20px'],
        ],
        '#value' => 'This Text is Colored With Your Selected Color.',
      ];
    }
    return $elements;
  }

}
