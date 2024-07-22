<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;
use Ock\DependencyInjection\Attribute\Service;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Ock\Ock\Text\TextInterface;

/**
 * Formula where the value is the name of an entity type with one or more fields.
 */
#[Service]
class Formula_EntityType_WithFields implements Formula_FlatSelectInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param \Drupal\Core\Entity\EntityTypeRepositoryInterface $entityTypeRepository
   */
  public function __construct(
    #[GetService('entity_field.manager')]
    private readonly EntityFieldManagerInterface $entityFieldManager,
    #[GetService('entity_type.repository')]
    private readonly EntityTypeRepositoryInterface $entityTypeRepository,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getOptions(): array {

    /**
     * @var array[][] $map
     *   Format: $[$entity_type][$field_name] = ['type' => $field_type, 'bundles' => $bundles]
     */
    $map = $this->entityFieldManager->getFieldMap();

    $etLabels = $this->entityTypeRepository->getEntityTypeLabels();

    $options = array_intersect_key($etLabels, $map);

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): ?TextInterface {

    /**
     * @var array[][] $map
     *   Format: $[$entity_type][$field_name] = ['type' => $field_type, 'bundles' => $bundles]
     */
    $map = $this->entityFieldManager->getFieldMap();

    $etLabels = $this->entityTypeRepository->getEntityTypeLabels();

    if (!isset($map[$id], $etLabels[$id])) {
      return NULL;
    }

    return $etLabels[$id];
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown(string|int $id): bool {

    /**
     * @var array[][] $map
     *   Format: $[$entity_type][$field_name] = ['type' => $field_type, 'bundles' => $bundles]
     */
    $map = $this->entityFieldManager->getFieldMap();

    $etLabels = $this->entityTypeRepository->getEntityTypeLabels();

    return isset($map[$id], $etLabels[$id]);
  }

}
