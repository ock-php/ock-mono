<?php

namespace Drupal\renderkit\EntityDisplay;

/**
 * Base class for entity display handlers that takes away the buildOne() method,
 * so inheriting classes only need to implement the buildMultiple() method.
 */
abstract class EntityDisplayMultipleBase implements EntityDisplayInterface {

  /**
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   */
  final function buildOne($entity_type, $entity) {
    $builds = $this->buildMultiple($entity_type, array($entity));
    return isset($builds[0])
      ? $builds[0]
      : array();
  }
}
