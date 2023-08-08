<?php

namespace Drupal\field_practice\Plugin\Field\FieldWidgetBase;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This is to provide the basic functionality for RGBColor field type's widgets.
 *
 * Basically this class extends the WidgetBase class and checks the conditions
 * required for widget of the RGBColor field type's visibilty.
 */
class RGBWidgetBase extends WidgetBase {

  /**
   * This is to set the widget access based on permission.
   *
   * @var bool
   */
  protected bool $widgetAcess = FALSE;

  /**
   * Constructs a HexCode object.
   *
   * @param string $plugin_id
   *   The plugin_id for the widget.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the widget is associated.
   * @param array $settings
   *   The widget settings.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   Stores the object of cuurent user acoount.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, AccountProxyInterface $current_user) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    if (in_array('administrator', $current_user->getRoles())) {
      $this->widgetAcess = TRUE;
    }
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('current_user'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, $element, &$form, FormStateInterface $form_state) {

  }

}
