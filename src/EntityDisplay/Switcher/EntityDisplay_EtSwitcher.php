<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Switcher;

use Drupal\renderkit\EntityDisplay\EntityDisplay_GroupByTypeBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

/**
 * Uses a different display handler depending on the entity type.
 */
class EntityDisplay_EtSwitcher extends EntityDisplay_GroupByTypeBase {

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
  private $typeDisplays = [];

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
   * @param string $entityTypeId
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   */
  protected function typeBuildEntities($entityTypeId, array $entities) {

    if (isset($this->typeDisplays[$entityTypeId])) {
      return $this->typeDisplays[$entityTypeId]->buildEntities($entities);
    }

    if (NULL !== $this->fallbackDisplay) {
      return $this->fallbackDisplay->buildEntities($entities);
    }

    return [];
  }

}
