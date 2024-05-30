<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface;
use Drupal\renderkit\EntityDisplay\EntityDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

class EntityDisplay_WithEntityBuildProcessor extends EntityDisplayBase {

  /**
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $entityDisplay
   * @param \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface|NULL $processor
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   *
   * @todo Mark as decorator.
   */
  #[OckPluginInstance('processedEntityDisplay', 'Processed entity display')]
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
   * {@inheritdoc}
   */
  public function buildEntity(EntityInterface $entity): array {
    $build = $this->entityDisplay->buildEntity($entity);
    return $this->processor->processEntityBuild($build, $entity);
  }
}
