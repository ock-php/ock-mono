<?php

namespace Drupal\renderkit\EntityDisplay\Group;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

/**
 * A group of entity display handlers, whose results are assembled into a single
 * render array.
 *
 * This can be used for something like a layout region with a number of fields
 * or elements.
 */
class EntityDisplayGroup implements EntityDisplayInterface {

  /**
   * @var EntityDisplayInterface[]
   */
  protected $displayHandlers;

  /**
   * @param EntityDisplayInterface[] $displayHandlers
   */
  function __construct(array $displayHandlers) {
    $this->displayHandlers = $displayHandlers;
  }

  /**
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildMultiple($entity_type, array $entities) {
    $builds = array();
    foreach ($this->displayHandlers as $name => $handler) {
      foreach ($handler->buildMultiple($entity_type, $entities) as $delta => $entity_build) {
        unset($entity_build['#weight']);
        $builds[$delta][$name] = $entity_build;
      }
    }
    return $builds;
  }
}
