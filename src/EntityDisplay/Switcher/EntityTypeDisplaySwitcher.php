<?php

namespace Drupal\renderkit\EntityDisplay\Switcher;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\EntityDisplay\EntitiesDisplaysBase;

/**
 * Uses a different display handler depending on the entity type.
 */
class EntityTypeDisplaySwitcher extends EntitiesDisplaysBase {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface|null
   */
  private $fallbackDisplay;

  /**
   * The displays to use per entity type.
   *
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface[]
   *   Format: $[$entityType] = $displayHandler
   */
  private $typeDisplays = array();

  /**
   * Sets a fallback entity display handler to use for entity types where no
   * specific handler is configured.
   *
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $decorated
   *
   * @return $this
   */
  function setFallbackDisplay(EntityDisplayInterface $decorated) {
    $this->fallbackDisplay = $decorated;
    return $this;
  }

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
   * Builds render arrays from the entities provided.
   *
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   */
  function buildMultiple($entity_type, array $entities) {
    return isset($this->typeDisplays[$entity_type])
      ? $this->typeDisplays[$entity_type]->buildMultiple($entity_type, $entities)
      : (isset($this->fallbackDisplay)
        ? $this->fallbackDisplay->buildMultiple($entity_type, $entities)
        : array());
  }

}
