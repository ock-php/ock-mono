<?php

namespace Drupal\renderkit\EntityBuildProcessor;

use Drupal\renderkit\EntityDisplay\Wrapper\NeutralEntityWrapper;

/**
 * Base class for entity build processor classes that only want to implement the
 * processOne method.
 */
abstract class EntityBuildProcessorMultipleBase extends NeutralEntityWrapper implements EntityBuildProcessorInterface {

  /**
   * Builds render arrays from the entities provided.
   *
   * Both the entities and the resulting render arrays are in plural, to allow
   * for more performant implementations.
   *
   * Array keys and their order must be preserved, although implementations
   * might remove some keys that are empty.
   *
   * @param string $entity_type
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  final function buildMultiple($entity_type, array $entities) {
    $builds = parent::buildMultiple($entity_type, $entities);
    return !empty($builds)
      ? $this->processMultiple($builds, $entity_type, $entities)
      : array();
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
  final function processOne(array $build, $entity_type, $entity) {
    if (empty($build)) {
      return array();
    }
    $builds = $this->processMultiple(array($build), $entity_type, array($entity));
    return isset($builds[0])
      ? $builds[0]
      : array();
  }
}
