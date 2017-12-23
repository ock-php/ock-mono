<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Select\CfSchema_SelectInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;

/**
 * Schema for a view mode machine name for a given entity type.
 *
 * This is currently not used anywhere, but may be used by whoever finds it
 * useful.
 */
class CfSchema_EntityViewModeName implements CfSchema_SelectInterface {

  /**
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  private $entityDisplayRepository;

  /**
   * @var string
   */
  private $entityType;

  /**
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entityDisplayRepository
   * @param string $entityType
   */
  public function __construct(EntityDisplayRepositoryInterface $entityDisplayRepository, $entityType) {
    $this->entityDisplayRepository = $entityDisplayRepository;
    $this->entityType = $entityType;
  }

  /**
   * @return string[][]
   */
  public function getGroupedOptions() {

    $options = $this->entityDisplayRepository
      ->getViewModeOptions($this->entityType);

    return ['' => $options];
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  public function idGetLabel($id): ?string {

    $options = $this->entityDisplayRepository
      ->getViewModeOptions($this->entityType);

    return isset($options[$id])
      ? $options[$id]
      : NULL;
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id) {

    $options = $this->entityDisplayRepository
      ->getViewModeOptions($this->entityType);

    return isset($options[$id]);
  }
}
