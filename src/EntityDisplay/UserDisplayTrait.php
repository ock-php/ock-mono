<?php

namespace Drupal\renderkit\EntityDisplay;

trait UserDisplayTrait {

  /**
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   */
  final public function buildEntity($entity_type, $entity) {
    if ('user' !== $entity_type) {
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
