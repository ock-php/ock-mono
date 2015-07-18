<?php

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\renderkit\EntityDisplay\Decorator\NeutralDecorator;

abstract class DecoratorBase extends NeutralDecorator {

  /**
   * @param string $entity_type
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildMultiple($entity_type, array $entities) {
    $builds = parent::buildMultiple($entity_type, $entities);
    foreach ($entities as $delta => $entity) {
      if (isset($builds[$delta])) {
        $builds[$delta] = $this->decorateOne($builds[$delta], $entity_type, $entity);
      }
    }
    return $builds;
  }

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Modified render array for the given entity.
   */
  abstract protected function decorateOne($build, $entity_type, $entity);
}
