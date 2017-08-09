<?php

namespace Drupal\renderkit8\EntityDisplay;

class EntityDisplay_FixedBuild extends EntityDisplayBase {

  /**
   * @var array
   */
  private $fixedRenderArray;

  /**
   * @param array $fixedRenderArray
   */
  public function __construct(array $fixedRenderArray) {
    $this->fixedRenderArray = $fixedRenderArray;
  }

  /**
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Render array for one entity.
   */
  public function buildEntity($entity_type, $entity) {
    return $this->fixedRenderArray;
  }
}
