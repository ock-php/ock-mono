<?php
declare(strict_types=1);

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
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   */
  public function buildEntities(array $entities) {
    if (self::$recursionDepth > $this->recursionLimit) {
      throw new EntityDisplayRecursionException();
    }
    ++self::$recursionDepth;
    $builds = parent::buildEntities($entities);
    --self::$recursionDepth;
    return $builds;
  }

}
