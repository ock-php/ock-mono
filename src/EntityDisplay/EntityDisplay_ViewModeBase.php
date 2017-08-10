<?php

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\Core\Entity\EntityTypeManagerInterface;

abstract class EntityDisplay_ViewModeBase extends EntitiesDisplayBase {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   *
   * @see entity_view()
   * @see node_view_multiple()
   */
  public function buildEntities(array $entities) {

    if ([] === $entities) {
      // In Drupal 7, entity_view() and even more so node_view_multiple() had
      // problems with $entities === [].
      // In Drupal 8? Dunno, let's not push our luck.
      return [];
    }

    $entitiesGrouped = [];
    foreach ($entities as $delta => $entity) {
      $entitiesGrouped[$entity->getEntityTypeId()][$delta] = $entity;
    }

    $buildsUnsorted = [];
    foreach ($entitiesGrouped as $entityTypeId => $typeEntities) {
      $builder = $this->entityTypeManager->getViewBuilder($entityTypeId);
      $viewMode = $this->etGetViewMode($entityTypeId);
      $buildsUnsorted += $builder->viewMultiple($typeEntities, $viewMode);
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
   * @param string $entityType
   *
   * @return string|null
   */
  abstract protected function etGetViewMode($entityType);
}
