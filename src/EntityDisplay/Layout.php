<?php

namespace Drupal\renderkit\EntityDisplay;

class Layout implements EntityDisplayInterface {

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
   * @param EntityDisplayInterface[] $regionDisplayHandlers
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
  function buildMultiple($entity_type, array $entities) {
    $builds = array();
    foreach ($this->regionDisplayHandlers as $name => $handler) {
      foreach ($handler->buildMultiple($entity_type, $entities) as $delta => $entity_build) {
        $builds[$delta][$name] = $entity_build;
      }
    }
    foreach ($builds as $delta => $entity_builds) {
      $builds[$delta]['#theme'] = $this->themeHook;
    }
    return $builds;
  }
}
