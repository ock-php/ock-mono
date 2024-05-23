<?php

declare(strict_types = 1);

namespace Drupal\renderkit\EntitySelection;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * @template T as \Drupal\Core\Entity\EntityInterface
 */
class EntitySelection_All implements EntitySelectionInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   */
  public function __construct(
    protected readonly EntityStorageInterface $storage,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getEntityTypeId(): string {
    return $this->storage->getEntityTypeId();
  }

  /**
   * {@inheritdoc}
   */
  public function getEntities(): array {
    return $this->storage->loadMultiple();
  }

  /**
   * {@inheritdoc}
   */
  public function idGetEntity(string|int $id): ?EntityInterface {
    return $this->storage->load($id);
  }

}
