<?php
declare(strict_types=1);

namespace Drupal\renderkit\Util;

final class EntityUtil extends UtilBase {

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Format: $[$delta] = $entity
   *
   * @return \Drupal\Core\Entity\EntityInterface[][]
   *   Format: $[$type][$delta] = $entity
   */
  public static function entitiesGroupByType(array $entities): array {

    $grouped = [];
    foreach ($entities as $delta => $entity) {
      $grouped[$entity->getEntityTypeId()][$delta] = $entity;
    }

    return $grouped;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Format: $[$delta] = $entity
   * @param string $entityTypeId
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   Format: $[$delta] = $entity
   */
  public static function entitiesFilterByType(array $entities, string $entityTypeId): array {

    $filtered = [];
    foreach ($entities as $delta => $entity) {
      if ($entityTypeId === $entity->getEntityTypeId()) {
        $filtered[$delta] = $entity;
      }
    }

    return $filtered;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Format: $[$delta] = $entity
   * @param string $class
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   Format: $[$delta] = $entity
   */
  public static function entitiesFilterByClass(array $entities, string $class): array {

    $filtered = [];
    foreach ($entities as $delta => $entity) {
      if ($entity instanceof $class) {
        $filtered[$delta] = $entity;
      }
    }

    return $filtered;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Format: $[$delta] = $entity
   * @param string $class
   * @param string $entityTypeId
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   Format: $[$delta] = $entity
   */
  public static function entitiesFilterByClassAndType(array $entities, string $class, string $entityTypeId): array {

    $filtered = [];
    foreach ($entities as $delta => $entity) {
      if (1
        && $entity instanceof $class
        && $entityTypeId === $entity->getEntityTypeId()
      ) {
        $filtered[$delta] = $entity;
      }
    }

    return $filtered;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return int[]
   */
  public static function entitiesGetIds(array $entities): array {

    $idsByDelta = [];
    foreach ($entities as $delta => $entity) {
      $idsByDelta[$delta] = $entity->id();
    }

    return $idsByDelta;
  }

}
