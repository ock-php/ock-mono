<?php

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;

trait UserDisplayTrait {

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *
   * @see \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface::buildEntity()
   */
  final public function buildEntity(EntityInterface $entity) {

    if ('user' !== $entity->getEntityTypeId()) {
      return [];
    }

    return $this->buildUser($entity);
  }

  /**
   * @param string $entity_type
   * @param object[] $users
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   * @throws \Exception
   */
  final public function buildEntities($entity_type, array $users) {
    if ('user' !== $entity_type) {
      return [];
    }
    $builds = [];
    foreach ($users as $delta => $user) {
      $builds[$delta] = $this->buildUser($user);
    }
    return $builds;
  }

  /**
   * @param object $user
   *
   * @return array
   *   Render array for the user object.
   */
  abstract protected function buildUser($user);

}
