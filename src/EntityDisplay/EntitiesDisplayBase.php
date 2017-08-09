<?php

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;

/**
 * Base class for entity display handlers that takes away the buildOne() method,
 * so inheriting classes only need to implement the buildMultiple() method.
 */
abstract class EntitiesDisplayBase implements EntityDisplayInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  final public function buildEntity(EntityInterface $entity) {
    $builds = $this->buildEntities($entity_type, [$entity]);
    return isset($builds[0])
      ? $builds[0]
      : [];
  }
}
