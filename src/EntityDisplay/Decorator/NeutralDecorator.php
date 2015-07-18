<?php

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

/**
 * A decorator that does not alter the result from the decorated display.
 */
class NeutralDecorator implements EntityDisplayInterface {

  /**
   * @var EntityDisplayInterface
   */
  protected $decorated;

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $decorated
   */
  function __construct(EntityDisplayInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $entity_type
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildMultiple($entity_type, array $entities) {
    // Return the unmodified result.
    return $this->decorated->buildMultiple($entity_type, $entities);
  }

}
