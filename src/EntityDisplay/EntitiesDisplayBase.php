<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

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
    $builds = $this->buildEntities([$entity]);
    return $builds[0] ?? [];
  }
}
