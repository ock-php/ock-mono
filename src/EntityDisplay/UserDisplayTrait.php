<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;
use Drupal\user\UserInterface;

trait UserDisplayTrait {

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   *
   * @see \Drupal\renderkit\EntityDisplay\EntityDisplayInterface::buildEntities()
   */
  final public function buildEntities(array $entities) {

    $builds = [];
    foreach ($entities as $delta => $entity) {
      if ($entity instanceof UserInterface) {
        $builds[$delta] = $this->buildUser($entity);
      }
    }

    return $builds;
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *
   * @see \Drupal\renderkit\EntityDisplay\EntityDisplayInterface::buildEntity()
   */
  final public function buildEntity(EntityInterface $entity) {

    if (!$entity instanceof UserInterface) {
      return [];
    }

    return $this->buildUser($entity);
  }

  /**
   * @param \Drupal\user\UserInterface $user
   *
   * @return array
   */
  abstract protected function buildUser(UserInterface $user);

}
