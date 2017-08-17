<?php

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\Core\Entity\EntityTypeManagerInterface;

abstract class EntityDisplay_ViewModeBase extends EntityDisplay_GroupByTypeBase {

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
   * @param string $entityTypeId
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   */
  protected function typeBuildEntities($entityTypeId, array $entities) {

    $builder = $this->entityTypeManager->getViewBuilder($entityTypeId);
    $viewMode = $this->etGetViewMode($entityTypeId);

    $builds = [];
    foreach ($entities as $delta => $entity) {
      $builds[$delta] = $builder->view($entity, $viewMode);
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
