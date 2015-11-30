<?php

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;
use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

class BuildProcessedEntityDisplay extends EntitiesDisplayBase{

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  private $entityDisplay;

  /**
   * @var \Drupal\renderkit\BuildProcessor\BuildProcessorInterface
   */
  private $processor;

  /**
   * @UniPlugin(
   *   id = "processedEntityDisplay",
   *   label = @Translate("Processed entity display")
   * )
   *
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $entityDisplay
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $processor
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  static function create(EntityDisplayInterface $entityDisplay, BuildProcessorInterface $processor = NULL) {
    return NULL !== $processor
      ? new static($entityDisplay, $processor)
      : $entityDisplay;
  }

  /**
   * ProcessorEntityDisplayDecorator constructor.
   *
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $entityDisplay
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $processor
   */
  function __construct(EntityDisplayInterface $entityDisplay, BuildProcessorInterface $processor) {
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
   * @param string $entity_type
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildEntities($entity_type, array $entities) {
    $builds = $this->entityDisplay->buildEntities($entity_type, $entities);
    foreach ($builds as $delta => $build) {
      $builds[$delta] = $this->processor->process($build);
    }
    return $builds;
  }

}
