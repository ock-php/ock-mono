<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProvider;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\Formula\Formula_BuildProvider_EntityDisplay;

class BuildProvider_EntityDisplay implements BuildProviderInterface {

  /**
   * @var \Drupal\Core\Entity\EntityInterface
   */
  private $entity;

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  private $entityDisplay;

  /**
   * @CfrPlugin("entityDisplay", "Entity display")
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function formula() {
    return Formula_BuildProvider_EntityDisplay::createDrilldown();
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $entityDisplay
   */
  public function __construct(EntityInterface $entity, EntityDisplayInterface $entityDisplay) {
    $this->entity = $entity;
    $this->entityDisplay = $entityDisplay;
  }

  /**
   * @return array
   *   A render array.
   */
  public function build() {
    return $this->entityDisplay->buildEntity($this->entity);
  }
}
