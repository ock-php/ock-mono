<?php

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\Exception\EntityDisplayRecursionException;

/**
 * Class that can be mixed into a decorator stack to detect recursion.
 */
class EntityDisplayRecursionDetectionDecorator extends NeutralEntityDisplayDecorator {

  /**
   * @var int
   */
  private static $recursionDepth = 0;

  /**
   * @var int
   */
  private $recursionLimit;

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $decorated
   * @param int $recursionLimit
   */
  function __construct(EntityDisplayInterface $decorated, $recursionLimit = 20) {
    parent::__construct($decorated);
    $this->recursionLimit = $recursionLimit;
  }

  /**
   * @param string $entity_type
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   *
   * @throws \Drupal\renderkit\Exception\EntityDisplayRecursionException
   */
  function buildMultiple($entity_type, array $entities) {
    if (self::$recursionDepth > $this->recursionLimit) {
      throw new EntityDisplayRecursionException();
    }
    ++self::$recursionDepth;
    $builds = parent::buildMultiple($entity_type, $entities);
    --self::$recursionDepth;
    return $builds;
  }

}
