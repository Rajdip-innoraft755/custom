<?php

namespace Drupal\field_practice\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\field_practice\Plugin\Field\FieldWidgetBase\RGBWidgetBase;

/**
 * This class is to the make widget for giving input as red, green, blue format.
 *
 * @FieldWidget(
 *   id = "three_field",
 *   label = @Translation("RGB Three Field"),
 *   description = @Translation("Enter the Hex Code for the color"),
 *   field_types = {
 *     "RGB_color"
 *   }
 * )
 */
class ThreeField extends RGBWidgetBase {

  /**
   * This array is to maintain the rgb color key along with the field title.
   *
   * @var array
   */
  public $colorArray = [
    'r' => 'red',
    'g' => 'green',
    'b' => 'blue',
  ];

  /**
   * {@inheritDoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, $element, &$form, FormStateInterface $form_state) {
    $color = $items[$delta]->color ? Color::hexToRgb($items[$delta]->color) : [];
    $element['color'] = [
      '#access' => $this->widgetAcess,
      '#type' => 'details',
      '#title' => $this->t('RGB color'),
      '#description' => $this->t('Enter the Hex value without #'),
      '#element_validate' => [
        [$this, 'setValue'],
      ],
    ];
    foreach ($this->colorArray as $key => $title) {
      $element['color'][$key] = [
        '#title' => $title,
        '#description' => $this->t(
          'Enter the decimal value for @color color', ['@color' => $title]
        ),
        '#type' => 'number',
        '#max' => 256,
        '#min' => 0,
        '#default_value' => $color[$title] ?? '',
        '#size' => 3,
      ];
    }
    return $element;
  }

  /**
   * This function is to set the value of color in hexcode from the rgb input.
   *
   * @param array $element
   *   This is to get the element for which this function is called.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   This is to store the object of the FormStateInterface.
   */
  public function setValue($element, FormStateInterface $form_state) {
    foreach ($this->colorArray as $key => $details) {
      $value = $element[$key]['#value'];
      if ($value >= 0 && $value <= 256) {
        $color_mixing[] = $element[$key]['#value'];
      }
      else {
        $form_state->setError($element[$key], 'Please Enter a value between 0 to 256.');
        exit;
      }
    }
    $form_state->setValueForElement($element, Color::rgbToHex($color_mixing));
  }

}
