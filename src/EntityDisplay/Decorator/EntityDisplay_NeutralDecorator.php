<?php

namespace Drupal\renderkit8\EntityDisplay\Decorator;

use Drupal\renderkit8\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;

class EntityDisplay_NeutralDecorator extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface|null
   */
  private $decorated;

  /**
   * Sets the decorated entity display.
   *
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $decorated
   */
  public function __construct(EntityDisplayInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   */
  public function buildEntities(array $entities) {

    return NULL !== $this->decorated
      ? $this->decorated->buildEntities($entities)
      : [];
  }

}
