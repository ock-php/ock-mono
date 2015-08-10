<?php

namespace Drupal\renderkit\EntityDisplay\Group;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\EntityDisplay\EntitiesDisplaysBase;

/**
 * A group of entity display handlers acting as regions in a layout.
 */
class EntityLayout extends EntitiesDisplaysBase {

  /**
   * @var string
   */
  protected $themeHook;

  /**
   * @var EntityDisplayInterface[]
   */
  protected $regionDisplayHandlers;

  /**
   * @param string $themeHook
   *   The name of a theme hook to render the layout.
   * @param EntityDisplayInterface[] $regionDisplayHandlers
   *   The entity display handlers for each layout region.
   */
  function __construct($themeHook, array $regionDisplayHandlers) {
    $this->themeHook = $themeHook;
    $this->regionDisplayHandlers = $regionDisplayHandlers;
  }

  /**
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildEntities($entity_type, array $entities) {
    $builds = array();
    foreach ($this->regionDisplayHandlers as $name => $handler) {
      foreach ($handler->buildEntities($entity_type, $entities) as $delta => $entity_build) {
        $builds[$delta][$name] = $entity_build;
      }
    }
    foreach ($builds as $delta => $entity_builds) {
      $builds[$delta]['#theme'] = $this->themeHook;
    }
    return $builds;
  }
}
