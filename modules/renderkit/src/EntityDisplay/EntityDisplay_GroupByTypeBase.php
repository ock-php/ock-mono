<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;

abstract class EntityDisplay_GroupByTypeBase implements EntityDisplayInterface {

  /**
   * {@inheritdoc}
   */
  final public function buildEntities(array $entities): array {

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
   * {@inheritdoc}
   */
  final public function buildEntity(EntityInterface $entity): array {

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
  abstract protected function typeBuildEntities(string $entityTypeId, array $entities): array;
}
