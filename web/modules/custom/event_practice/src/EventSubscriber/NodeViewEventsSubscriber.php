<?php

namespace Drupal\event_practice\EventSubscriber;

use Drupal\Core\Config\Config;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Messenger\Messenger;
use Drupal\event_practice\Event\NodeViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class EntityTypeSubscriber.
 *
 * @package Drupal\event_practice\EventSubscriber
 */
class NodeViewEventsSubscriber implements EventSubscriberInterface {

  /**
   * This is to store the config object.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected Config $config;

  /**
   * This is to store the messenger object.
   *
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected Messenger $messenger;

  /**
   * This is to construct the NodeViewEventSubscriber object.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config
   *   This is the configFactory object.
   * @param \Drupal\Core\Messenger\Messenger $messenger
   *   This is the messenger object.
   */
  public function __construct(ConfigFactory $config, Messenger $messenger) {
    $this->config = $config->get('entity_practice.budget');
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    return [
      NodeViewEvent::NODE_VIEW => 'budgetStatus',
    ];
  }

  /**
   * React to a config object being saved.
   *
   * @param \Drupal\event_practice\Event\NodeViewEvent $event
   *   NodeView event.
   */
  public function budgetStatus(NodeViewEvent $event) {
    $bundle = $event->node->bundle();
    if ($bundle === 'movie') {
      $price = $event->node->get('field_entity_practice_price')->value;
      $budget = $this->config->get('budget');
      $status = '';
      if ($budget && $price) {
        if ($price < $budget) {
          $status = 'under';
        }
        elseif ($price > $budget) {
          $status = 'over';
        }
        else {
          $status = 'within';
        }
        $this->messenger->addStatus(t('This movie is @status budget from
          event', ['@status' => $status]));
      }
    }
  }

}
