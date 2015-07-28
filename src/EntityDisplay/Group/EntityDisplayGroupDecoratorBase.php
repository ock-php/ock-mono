<?php

namespace Drupal\renderkit\EntityDisplay\Group;

abstract class EntityDisplayGroupDecoratorBase extends EntityDisplayGroup {

  /**
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildMultiple($entity_type, array $entities) {
    $builds = parent::buildMultiple($entity_type, $entities);
    foreach ($entities as $delta => $entity) {
      $builds[$delta] = $this->decorateOne($builds[$delta], $entity_type, $entity);
    }
    return $builds;
  }

  /**
   * @param array $build
   * @param string $entity_type
   * @param object $entity
   *
   * @return array Render array for one entity.
   * Render array for one entity.
   */
  abstract protected function decorateOne($build, $entity_type, $entity);
}
