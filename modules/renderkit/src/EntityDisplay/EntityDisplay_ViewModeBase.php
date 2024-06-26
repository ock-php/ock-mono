<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\Core\Entity\EntityTypeManagerInterface;

abstract class EntityDisplay_ViewModeBase extends EntityDisplay_GroupByTypeBase {

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
  ) {}

  /**
   * @param string $entityTypeId
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   */
  protected function typeBuildEntities(string $entityTypeId, array $entities): array {

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
  abstract protected function etGetViewMode(string $entityType): ?string;
}
