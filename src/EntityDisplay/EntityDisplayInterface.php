<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;

/**
 * Interface for entity display handlers.
 *
 * An entity display handler builds render arrays from entities. Depending on
 * implementation, the render array could be for the whole entity, or just a
 * part of it (a title, a field, etc).
 *
 * Implementing classes should use one of the base classes, so they only need to
 * implement one of the two methods, either buildEntities() or buildEntity().
 *
 * @see \Drupal\renderkit8\EntityDisplay\EntityDisplayBase
 * @see \Drupal\renderkit8\EntityDisplay\EntitiesDisplaysBase
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
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
   *
   * @return array[]
   */
  public function buildEntities(array $entities);

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity);
}
