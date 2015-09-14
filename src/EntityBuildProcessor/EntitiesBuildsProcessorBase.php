<?php

namespace Drupal\renderkit\EntityBuildProcessor;

use Drupal\renderkit\EntityDisplay\Decorator\OptionalEntityDisplayDecorator;

/**
 * Base class for entity build processor classes that only want to implement the
 * processOne method.
 */
abstract class EntitiesBuildsProcessorBase extends OptionalEntityDisplayDecorator implements EntityBuildProcessorInterface {

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
  final function buildEntities($entity_type, array $entities) {
    $builds = parent::buildEntities($entity_type, $entities);
    return !empty($builds)
      ? $this->processEntitiesBuilds($builds, $entity_type, $entities)
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
  final function processEntityBuild(array $build, $entity_type, $entity) {
    if (empty($build)) {
      return array();
    }
    $builds = $this->processEntitiesBuilds(array($build), $entity_type, array($entity));
    return isset($builds[0])
      ? $builds[0]
      : array();
  }
}
