<?php

namespace Drupal\renderkit8\EntityDisplay\Decorator;

use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit8\Exception\EntityDisplayRecursionException;

/**
 * Class that can be mixed into a decorator stack to detect recursion.
 */
class EntityDisplay_RecursionDetectionDecorator extends EntityDisplay_NeutralDecorator {

  /**
   * @var int
   */
  private static $recursionDepth = 0;

  /**
   * @var int
   */
  private $recursionLimit;

  /**
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $decorated
   * @param int $recursionLimit
   */
  public function __construct(EntityDisplayInterface $decorated, $recursionLimit = 20) {
    parent::__construct($decorated);
    $this->recursionLimit = $recursionLimit;
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   *
   * @throws \Drupal\renderkit8\Exception\EntityDisplayRecursionException
   */
  public function buildEntities($entityType, array $entities) {
    if (self::$recursionDepth > $this->recursionLimit) {
      throw new EntityDisplayRecursionException();
    }
    ++self::$recursionDepth;
    $builds = parent::buildEntities($entityType, $entities);
    --self::$recursionDepth;
    return $builds;
  }

}
