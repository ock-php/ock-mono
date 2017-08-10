<?php

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

class EntityDisplay_NeutralDecorator extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface|null
   */
  private $decorated;

  /**
   * Sets the decorated entity display.
   *
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $decorated
   */
  public function __construct(EntityDisplayInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  public function buildEntities($entityType, array $entities) {
    return NULL !== $this->decorated
      ? $this->decorated->buildEntities($entityType, $entities)
      : [];
  }

}
