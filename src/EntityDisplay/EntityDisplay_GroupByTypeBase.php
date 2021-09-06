<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;

abstract class EntityDisplay_GroupByTypeBase implements EntityDisplayInterface {

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
  final public function buildEntities(array $entities) {

    if ([] === $entities) {
      // In Drupal 7, entity_view() and even more so node_view_multiple() had
      // problems with $entities === [].
      // In Drupal 8? Dunno, let's not push our luck.
      return [];
    }

    $grouped = [];
    foreach ($entities as $delta => $entity) {
      $grouped[$entity->getEntityTypeId()][$delta] = $entity;
    }

    $buildsUnsorted = [];
    foreach ($grouped as $entityTypeId => $typeEntities) {
      $buildsUnsorted += $this->typeBuildEntities(
        $entityTypeId,
        $typeEntities);
    }

    $builds = [];
    foreach ($entities as $delta => $entity) {
      if (isset($buildsUnsorted[$delta])) {
        $builds[$delta] = $buildsUnsorted[$delta];
      }
    }

    return $builds;
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  final public function buildEntity(EntityInterface $entity) {

    $builds = $this->typeBuildEntities(
      $entity->getEntityTypeId(),
      [$entity]);

    return $builds[0] ?? [];
  }

  /**
   * @param string $entityTypeId
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   */
  abstract protected function typeBuildEntities($entityTypeId, array $entities);
}
