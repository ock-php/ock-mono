<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface;
use Drupal\renderkit\EntityDisplay\EntityDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

class EntityDisplay_WithEntityBuildProcessor extends EntityDisplayBase {

  /**
   * @CfrPlugin(
   *   id = "processedEntityDisplay",
   *   label = @Translate("Processed entity display")
   * )
   *
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $entityDisplay
   * @param \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface|NULL $processor
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  public static function create(EntityDisplayInterface $entityDisplay, EntityBuildProcessorInterface $processor = NULL): EntityDisplayInterface {
    return NULL !== $processor
      ? new static($entityDisplay, $processor)
      : $entityDisplay;
  }

  /**
   * ProcessorEntityDisplayDecorator constructor.
   *
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $entityDisplay
   * @param \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface $processor
   */
  public function __construct(
    private readonly EntityDisplayInterface $entityDisplay,
    private readonly EntityBuildProcessorInterface $processor,
  ) {}

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity): array {
    $build = $this->entityDisplay->buildEntity($entity);
    return $this->processor->processEntityBuild($build, $entity);
  }
}
