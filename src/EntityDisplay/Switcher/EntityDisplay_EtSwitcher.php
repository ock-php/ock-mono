<?php

namespace Drupal\renderkit\EntityDisplay\Switcher;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;

/**
 * Uses a different display handler depending on the entity type.
 */
class EntityDisplay_EtSwitcher extends EntitiesDisplayBase {

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
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $fallbackDisplay
   *
   * @return $this
   */
  public function setFallbackDisplay(EntityDisplayInterface $fallbackDisplay) {
    $this->fallbackDisplay = $fallbackDisplay;
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
  public function entityTypeSetDisplay($entityType, EntityDisplayInterface $display) {
    $this->typeDisplays[$entityType] = $display;

    return $this;
  }

  /**
   * Builds render arrays from the entities provided.
   *
   * @param string $entityType
   * @param object[] $entities
   *
   * @return array[]
   */
  public function buildEntities($entityType, array $entities) {
    return isset($this->typeDisplays[$entityType])
      ? $this->typeDisplays[$entityType]->buildEntities($entityType, $entities)
      : (isset($this->fallbackDisplay)
        ? $this->fallbackDisplay->buildEntities($entityType, $entities)
        : array());
  }

}
