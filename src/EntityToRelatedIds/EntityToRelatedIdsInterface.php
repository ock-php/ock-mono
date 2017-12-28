<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityToRelatedIds;

use Drupal\Core\Entity\EntityInterface;

interface EntityToRelatedIdsInterface {

  /**
   * @return string
   */
  public function getTargetType(): string;

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return int[]
   *   Format: $[] = $relatedEntityId
   */
  public function entityGetRelatedIds(EntityInterface $entity): array;

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return int[][]
   *   Format: $[$delta][] = $relatedEntityId
   */
  public function entitiesGetRelatedIds(array $entities): array;

}
