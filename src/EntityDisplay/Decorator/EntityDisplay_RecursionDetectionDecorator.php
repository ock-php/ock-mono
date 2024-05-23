<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\Exception\EntityDisplayRecursionException;

/**
 * Class that can be mixed into a decorator stack to detect recursion.
 */
class EntityDisplay_RecursionDetectionDecorator extends EntitiesDisplayBase {

  /**
   * @var int
   */
  private static int $recursionDepth = 0;

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $decorated
   * @param int $recursionLimit
   */
  public function __construct(
    private readonly EntityDisplayInterface $decorated,
    private readonly int $recursionLimit = 20,
  ) {}

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   */
  public function buildEntities(array $entities): array {
    if (self::$recursionDepth > $this->recursionLimit) {
      throw new EntityDisplayRecursionException();
    }
    try {
      ++self::$recursionDepth;
      return $this->decorated->buildEntities($entities);
    }
    finally {
      --self::$recursionDepth;
    }
  }

}
