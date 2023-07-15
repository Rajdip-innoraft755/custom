<?php

use Drupal\Core\Entity\EntityInterface;

/**
 * @file
 *  This files represents the custom hooks declaration.
 */

/**
 * hook_node_view_count
 *  This hook is written for the counting the number of view for a perticular
 *  node.
 *
 *   @param EntityInterface $entity
 *     Accepts the current entity object as only parameter.
 */
function hook_node_view_count(EntityInterface $entity) {

}
