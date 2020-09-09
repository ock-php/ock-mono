<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Select\Flat\CfSchema_FlatSelectInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;

/**
 * Schema where the value is the name of an entity type with one or more fields.
 */
class CfSchema_EntityType_WithFields implements CfSchema_FlatSelectInterface {

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
  public static function create() {
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
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id): bool {

    /**
     * @var array[][] $map
     *   Format: $[$entity_type][$field_name] = ['type' => $field_type, 'bundles' => $bundles]
     */
    $map = $this->entityFieldManager->getFieldMap();

    $etLabels = $this->entityTypeRepository->getEntityTypeLabels();

    return isset($map[$id], $etLabels[$id]);
  }
}
