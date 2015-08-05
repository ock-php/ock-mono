<?php

namespace Drupal\renderkit\EntityDisplay;

/**
 * The most boring entity display handler, ever.
 */
class EntityTitle extends EntityDisplayBase {

  /**
   * @param $entity_type
   * @param $entity
   *
   * @return array
   */
  function buildOne($entity_type, $entity) {
    return array(
      '#markup' => entity_label($entity_type, $entity),
    );
  }

}
