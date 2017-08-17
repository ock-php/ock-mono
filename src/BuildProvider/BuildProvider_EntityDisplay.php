<?php

namespace Drupal\renderkit8\BuildProvider;

use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal;
use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit8\Schema\CfSchema_BuildProvider_EntityDisplay;

class BuildProvider_EntityDisplay implements BuildProviderInterface {

  /**
   * @var \Drupal\Core\Entity\EntityInterface
   */
  private $entity;

  /**
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface
   */
  private $entityDisplay;

  /**
   * @CfrPlugin("entityDisplay", "Entity display")
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function schema() {
    $schema = new CfSchema_BuildProvider_EntityDisplay();
    return new CfSchema_DrilldownVal($schema, $schema);
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $entityDisplay
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
