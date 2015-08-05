<?php

namespace Drupal\renderkit\EntityDisplay\Switcher;

use Drupal\renderkit\EntityDisplay\Decorator\NeutralEntityDisplayDecorator;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

/**
 * Uses a different display handler depending on the entity type.
 *
 * The "decorated" display handler of the parent class is used as a fallback.
 */
class EntityTypeDisplaySwitcher extends NeutralEntityDisplayDecorator {

  /**
   * The displays to use per entity type.
   *
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[]
   *   Format: $[$entityType] = $displayHandler
   */
  private $typeDisplays = array();

  /**
   * Sets the display that will be used for entities of this type.
   *
   * @param string $entityType
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $display
   *
   * @return $this
   */
  function entityTypeSetDisplay($entityType, EntityDisplayInterface $display) {
    $this->typeDisplays[$entityType] = $display;

    return $this;
  }

  /**
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   */
  function buildMultiple($entity_type, array $entities) {
    return isset($this->typeDisplays[$entity_type])
      ? $this->typeDisplays[$entity_type]->buildMultiple($entity_type, $entities)
      // Use the "decorated" display as a fallback.
      : parent::buildMultiple($entity_type, $entities);
  }

}
