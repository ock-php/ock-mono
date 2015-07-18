<?php

namespace Drupal\renderkit\EntityDisplay;

class MissingHandler implements EntityDisplayInterface {

  /**
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildMultiple($entity_type, array $entities) {
    return array(
      '#markup' => t('Broken or missing entity display handler'),
    );
  }
}
