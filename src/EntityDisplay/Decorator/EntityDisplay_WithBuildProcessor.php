<?php

namespace Drupal\renderkit8\EntityDisplay\Decorator;

use Drupal\renderkit8\BuildProcessor\BuildProcessorInterface;
use Drupal\renderkit8\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;

class EntityDisplay_WithBuildProcessor extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface
   */
  private $entityDisplay;

  /**
   * @var \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface
   */
  private $processor;

  /**
   * @CfrPlugin(
   *   id = "processedEntityDisplay",
   *   label = @Translate("Processed entity display")
   * )
   *
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $entityDisplay
   * @param \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface $processor
   *
   * @return \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface
   */
  public static function create(EntityDisplayInterface $entityDisplay, BuildProcessorInterface $processor = NULL) {
    return NULL !== $processor
      ? new static($entityDisplay, $processor)
      : $entityDisplay;
  }

  /**
   * ProcessorEntityDisplayDecorator constructor.
   *
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $entityDisplay
   * @param \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface $processor
   */
  public function __construct(EntityDisplayInterface $entityDisplay, BuildProcessorInterface $processor) {
    $this->entityDisplay = $entityDisplay;
    $this->processor = $processor;
  }

  /**
   * Builds render arrays from the entities provided.
   *
   * Both the entities and the resulting render arrays are in plural, to allow
   * for more performant implementations.
   *
   * Array keys and their order must be preserved, although implementations
   * might remove some keys that are empty.
   *
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
   *
   * @return array[]
   */
  public function buildEntities(array $entities) {
    $builds = $this->entityDisplay->buildEntities($entities);
    foreach ($builds as $delta => $build) {
      $builds[$delta] = $this->processor->process($build);
    }
    return $builds;
  }

}
