<?php

namespace Drupal\renderkit\EntityDisplay;

/**
 * The most boring entity display handler, ever.
 */
class TitleEntityDisplay extends EntityDisplayBase {

  /**
   * @param $entity_type
   * @param $entity
   *
   * @return array
   */
  function buildEntity($entity_type, $entity) {
    return array(
      '#markup' => entity_label($entity_type, $entity),
    );
  }

}
