<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;

/**
 * Formula where the value is the name of an entity type with one or more fields.
 */
class Formula_EntityType_WithFields implements Formula_FlatSelectInterface {

  /**
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  private $entityFieldManager;

  /**
   * @var \Drupal\Core\Entity\EntityTypeRepositoryInterface
   */
  private $entityTypeRepository;

  /**
   * @return self
   */
  public static function create(): self {
    return new self(
      \Drupal::service('entity_field.manager'),
      \Drupal::service('entity_type.repository'));
  }

  /**
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param \Drupal\Core\Entity\EntityTypeRepositoryInterface $entityTypeRepository
   */
  public function __construct(
    EntityFieldManagerInterface $entityFieldManager,
    EntityTypeRepositoryInterface $entityTypeRepository
  ) {
    $this->entityFieldManager = $entityFieldManager;
    $this->entityTypeRepository = $entityTypeRepository;
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
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
  public function idGetLabel($id) {

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
