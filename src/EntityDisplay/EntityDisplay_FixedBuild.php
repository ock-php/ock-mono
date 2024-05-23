<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;

class EntityDisplay_FixedBuild extends EntityDisplayBase {

  /**
   * @param array $fixedRenderArray
   */
  public function __construct(
    private readonly array $fixedRenderArray,
  ) {}

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity): array {
    return $this->fixedRenderArray;
  }
}
