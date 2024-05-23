<?php

declare(strict_types = 1);

namespace Drupal\renderkit\EntitySelection;

use Drupal\Core\Entity\EntityInterface;

/**
 * @template T as \Drupal\Core\Entity\EntityInterface
 */
interface EntitySelectionInterface {

  public function getEntityTypeId(): string;

  /**
   * @return T[]
   */
  public function getEntities(): array;

  /**
   * @return T
   */
  public function idGetEntity(string|int $id): ?EntityInterface;

}
