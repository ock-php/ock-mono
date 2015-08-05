<?php

namespace Drupal\renderkit\EntityDisplay;

/**
 * Interface for entity display handlers.
 *
 * An entity display handler builds render arrays from entities. Depending on
 * implementation, the render array could be for the whole entity, or just a
 * part of it (a title, a field, etc).
 */
interface EntityDisplayInterface {

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
  function buildMultiple($entity_type, array $entities);

  /**
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   */
  function buildOne($entity_type, $entity);
}
