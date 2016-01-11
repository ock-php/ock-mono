<?php

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface;
use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

class EntityDisplay_WithEntityBuildProcessor extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  private $entityDisplay;

  /**
   * @var \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface
   */
  private $processor;

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
  static function create(EntityDisplayInterface $entityDisplay, EntityBuildProcessorInterface $processor = NULL) {
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
  function __construct(EntityDisplayInterface $entityDisplay, EntityBuildProcessorInterface $processor) {
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
   * @param string $entityType
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildEntities($entityType, array $entities) {
    $builds = $this->entityDisplay->buildEntities($entityType, $entities);
    return $this->processor->processEntitiesBuilds($builds, $entityType, $entities);
  }
}
