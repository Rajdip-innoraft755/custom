<?php

namespace Drupal\field_practice\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\field_practice\Plugin\Field\FieldWidgetBase\RGBWidgetBase;

/**
 * This class is to the make widget for giving input as hexcode.
 *
 * @FieldWidget(
 *   id = "hex_code",
 *   label = @Translation("HEX CODE"),
 *   description = @Translation("Enter the Hex Code for the color"),
 *   field_types = {
 *     "RGB_color"
 *   }
 * )
 */
class HexCode extends RGBWidgetBase {

  /**
   * {@inheritDoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, $element, &$form, FormStateInterface $form_state) {
    $color = $items[$delta]->color ?? '';
    $element['color'] = [
      '#access' => $this->widgetAcess,
      '#type' => 'textfield',
      '#size' => 8,
      '#title' => $this->t('Enter the Hex Code'),
      '#default_value' => $color,
      '#element_validate' => [
        [$this, 'validate'],
      ],
    ];
    return $element;
  }

  /**
   * This function is for validating the input given by the user.
   *
   * @param array $element
   *   This is to get the element for which this function is called.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   This is to store the object of the FormStateInterface.
   */
  public function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if (Color::validateHex($value)) {
      if (!str_starts_with($value, '#')) {
        $value = '#' . $value;
      }
      $form_state->setValueForElement($element, Color::normalizeHexLength($value));
    }
    else {
      $form_state->setError($element, 'Please Enter the hexcode in proper format');
    }
  }

}
