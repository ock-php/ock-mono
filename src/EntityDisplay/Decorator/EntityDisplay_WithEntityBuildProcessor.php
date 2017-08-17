<?php

namespace Drupal\renderkit8\EntityDisplay\Decorator;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit8\EntityBuildProcessor\EntityBuildProcessorInterface;
use Drupal\renderkit8\EntityDisplay\EntityDisplayBase;
use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;

class EntityDisplay_WithEntityBuildProcessor extends EntityDisplayBase {

  /**
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface
   */
  private $entityDisplay;

  /**
   * @var \Drupal\renderkit8\EntityBuildProcessor\EntityBuildProcessorInterface
   */
  private $processor;

  /**
   * @CfrPlugin(
   *   id = "processedEntityDisplay",
   *   label = @Translate("Processed entity display")
   * )
   *
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $entityDisplay
   * @param \Drupal\renderkit8\EntityBuildProcessor\EntityBuildProcessorInterface|NULL $processor
   *
   * @return \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface
   */
  public static function create(EntityDisplayInterface $entityDisplay, EntityBuildProcessorInterface $processor = NULL) {
    return NULL !== $processor
      ? new static($entityDisplay, $processor)
      : $entityDisplay;
  }

  /**
   * ProcessorEntityDisplayDecorator constructor.
   *
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $entityDisplay
   * @param \Drupal\renderkit8\EntityBuildProcessor\EntityBuildProcessorInterface $processor
   */
  public function __construct(EntityDisplayInterface $entityDisplay, EntityBuildProcessorInterface $processor) {
    $this->entityDisplay = $entityDisplay;
    $this->processor = $processor;
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity) {
    $build = $this->entityDisplay->buildEntity($entity);
    return $this->processor->processEntityBuild($build, $entity);
  }
}
