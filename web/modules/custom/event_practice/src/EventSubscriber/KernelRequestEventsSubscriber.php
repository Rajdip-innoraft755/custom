<?php

namespace Drupal\event_practice\EventSubscriber;

use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Drupal\event_practice\Event\NodeViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class KernelRequestEventsSubscriber.
 *
 * This class is responsible for dispatching the noveview event. It uses the
 * kernel request event which is dispatched by default and by this
 * implementation we can replace the hook for dispatching the nodeview event.
 *
 * @package Drupal\event_practice\EventSubscriber
 */
class KernelRequestEventsSubscriber implements EventSubscriberInterface {

  /**
   * This is to store the event dispatcher object.
   *
   * @var \Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher
   */
  protected ContainerAwareEventDispatcher $dispatcher;

  /**
   * This is to construct the KernelRequestEventsSubscriber object.
   *
   * @param \Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher $dispatcher
   *   This is the event dispatcher object.
   */
  public function __construct(ContainerAwareEventDispatcher $dispatcher) {
    $this->dispatcher = $dispatcher;
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => 'request',
    ];
  }

  /**
   * This function basically used to dispatch my the custom event.
   *
   * It passes the current node object to the.
   *
   * @param \Symfony\Component\HttpKernel\Event\ViewEvent $response_event
   *   This is the request event object.
   */
  public function request(RequestEvent $response_event) {
    // If the request is made for the node then only it dispatched
    // the NodeViewEvent.
    $node = $response_event->getRequest()->attributes->all()['node'] ?? '';
    if ($node) {
      $node_view_event = new NodeViewEvent($node);
      $this->dispatcher->dispatch($node_view_event, NodeViewEvent::NODE_VIEW);
    }
  }

}
