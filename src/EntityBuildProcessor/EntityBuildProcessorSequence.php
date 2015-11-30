<?php

namespace Drupal\renderkit\EntityBuildProcessor;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;

/**
 * An entity display decorator that runs the render array through a bunch of
 * processors.
 *
 * @todo Remove the optional decorator stuff?
 */
class EntityBuildProcessorSequence extends EntitiesBuildsProcessorDecoratorBase {

  /**
   * @var object[]
   */
  private $processors;

  /**
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $buildProcessor
   *
   * @return $this
   */
  function addBuildProcessor(BuildProcessorInterface $buildProcessor) {
    $this->processors[] = $buildProcessor;
    return $this;
  }

  /**
   * @param \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface $entityBuildProcessor
   *
   * @return $this
   */
  function addEntityBuildProcessor(EntityBuildProcessorInterface $entityBuildProcessor) {
    $this->processors[] = $entityBuildProcessor;
    return $this;
  }

  /**
   * Processes all the render arrays, by passing them through all the registered
   * processors.
   *
   * @param array[] $builds
   *   The render arrays produced by the decorated display handler.
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   *   Modified render arrays for the given entities.
   */
  function processEntitiesBuilds(array $builds, $entity_type, array $entities) {
    foreach ($this->processors as $processor) {
      if ($processor instanceof EntityBuildProcessorInterface) {
        $builds = $processor->processEntitiesBuilds($builds, $entity_type, $entities);
      }
      elseif ($processor instanceof BuildProcessorInterface) {
        foreach ($builds as $delta => $build) {
          if (!empty($build)) {
            $builds[$delta] = $processor->process($build);
          }
        }
      }
    }
    return $builds;
  }

}
