<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;

/**
 * Formula for a view mode machine name for a given entity type.
 *
 * This is currently not used anywhere, but may be used by whoever finds it
 * useful.
 */
class Formula_EntityViewModeName implements Formula_SelectInterface {

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
  public function getGroupedOptions(): array {

    $options = $this->entityDisplayRepository
      ->getViewModeOptions($this->entityType);

    return ['' => $options];
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id) {

    $options = $this->entityDisplayRepository
      ->getViewModeOptions($this->entityType);

    return $options[$id] ?? null;
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown(string|int $id): bool {

    $options = $this->entityDisplayRepository
      ->getViewModeOptions($this->entityType);

    return isset($options[$id]);
  }
}
