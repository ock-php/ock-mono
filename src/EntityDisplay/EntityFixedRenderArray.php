<?php

namespace Drupal\renderkit\EntityDisplay;

class EntityFixedRenderArray extends EntityDisplayBase {

  /**
   * @var array
   */
  private $fixedRenderArray;

  /**
   * @param array $fixedRenderArray
   */
  function __construct(array $fixedRenderArray) {
    $this->fixedRenderArray = $fixedRenderArray;
  }

  /**
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Render array for one entity.
   */
  function buildEntity($entity_type, $entity) {
    return $this->fixedRenderArray;
  }
}
