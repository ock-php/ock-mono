<?php

namespace Drupal\renderkit\EntityDisplay;

class EntityFixedHtml extends EntityDisplayBase {

  /**
   * @var string
   */
  private $fixedHtml;

  /**
   * @param string $html
   *   The fixed html to show for each entity.
   */
  function __construct($html) {
    $this->fixedHtml = $html;
  }

  /**
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Render array for one entity.
   */
  function buildOne($entity_type, $entity) {
    return array('#markup' => $this->fixedHtml);
  }
}
