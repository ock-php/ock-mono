<?php

namespace Drupal\renderkit8\EntityDisplay\Switcher;

use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit8\EntityDisplay\EntitiesDisplayBase;

/**
 * Uses a different display handler depending on the entity type.
 */
class EntityDisplay_EtSwitcher extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface|null
   */
  private $fallbackDisplay;

  /**
   * The displays to use per entity type.
   *
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface[]
   *   Format: $[$entityType] = $displayHandler
   */
  private $typeDisplays = [];

  /**
   * Sets a fallback entity display handler to use for entity types where no
   * specific handler is configured.
   *
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $fallbackDisplay
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
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $display
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
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   */
  public function buildEntities(array $entities) {

    if (isset($this->typeDisplays[$entityType])) {
      return $this->typeDisplays[$entityType]->buildEntities($entities);
    }

    if (NULL !== $this->fallbackDisplay) {
      return $this->fallbackDisplay->buildEntities($entities);
    }

    return [];
  }

}
