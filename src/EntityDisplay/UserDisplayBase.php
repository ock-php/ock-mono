<?php

namespace Drupal\renderkit\EntityDisplay;

abstract class UserDisplayBase implements EntityDisplayInterface {

  /**
   * @param string $entity_type
   * @param object[] $users
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   * @throws \Exception
   */
  function buildMultiple($entity_type, array $users) {
    if ('user' !== $entity_type) {
      throw new \Exception("Entity type must be 'user'.");
    }
    $builds = array();
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
