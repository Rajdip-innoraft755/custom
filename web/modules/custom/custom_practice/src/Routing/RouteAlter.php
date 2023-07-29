<?php

namespace Drupal\custom_practice\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;


/**
 * RouteAlter class is written for changing the existing route.
 * Here we make changes in custom_practice.dynamic route.
 */
class RouteAlter extends RouteSubscriberBase {

  /**
   * {@inheritDoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    // If the path is same as our desired route for which we want to reset
    // permissions then the requirements array of the route will be changed
    // which initially declared in the routing.yml file.

    // The logic is commented out here as the revokePermission() is in the same
    // work in .module file.

    // if ($route = $collection->get('custom_practice.dynamic')) {
    //   $route->setRequirements([
    //     '_role' => 'administrator',
    //   ]);
    // }
  }
}
