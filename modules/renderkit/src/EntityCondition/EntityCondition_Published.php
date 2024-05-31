<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityCondition;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * Checks whether the entity is published.
 */
#[OckPluginInstance('published', 'Entity is published')]
class EntityCondition_Published implements EntityConditionInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return bool
   */
  public function entityCheckCondition(EntityInterface $entity): bool {
    return $entity instanceof EntityPublishedInterface
      ? $entity->isPublished()
      : TRUE;
  }

}
