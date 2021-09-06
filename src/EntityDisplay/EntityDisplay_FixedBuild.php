<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;

class EntityDisplay_FixedBuild extends EntityDisplayBase {

  /**
   * @var array
   */
  private $fixedRenderArray;

  /**
   * @param array $fixedRenderArray
   */
  public function __construct(array $fixedRenderArray) {
    $this->fixedRenderArray = $fixedRenderArray;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity): array {
    return $this->fixedRenderArray;
  }
}
