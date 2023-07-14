<?php

namespace Drupal\field_practice\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\field_practice\Plugin\Field\FieldWidgetBase\RGBWidgetBase;

/**
 * This class is to the make widget for choosing color from the gradiant.
 *
 * @FieldWidget(
 *   id = "color_picker",
 *   label = @Translation("Color Picker"),
 *   field_types = {
 *     "RGB_color"
 *   }
 * )
 */
class ColorPicker extends RGBWidgetBase {

  /**
   * {@inheritDoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, $element, &$form, FormStateInterface $form_state) {
    $color = $items[$delta]->color ?? '';
    // dd($color);
    $element['color'] = [
      '#access' => $this->widgetAcess,
      '#type' => 'color',
      '#title' => $this->t('Choose the Color form Gradiant'),
      '#default_value' => $color,
    ];
    return $element;
  }

}
