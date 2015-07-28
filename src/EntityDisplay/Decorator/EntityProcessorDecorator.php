<?php

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\renderkit\BuildProvider\BuildProviderInterface;
use Drupal\renderkit\BuildProvider\StaticBuildProvider;
use Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface;
use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;

/**
 * An entity display decorator that runs the render array through a bunch of
 * processors.
 */
class EntityProcessorDecorator extends NeutralEntityDisplayDecorator {

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
   * @param \Drupal\renderkit\BuildProvider\BuildProviderInterface $fallbackBuildProvider
   *
   * @return $this
   */
  function addFallbackBuildProvider(BuildProviderInterface $fallbackBuildProvider) {
    $this->processors[] = $fallbackBuildProvider;
    return $this;
  }

  /**
   * @param array $fallbackBuild
   *   Render array to be used to replace empty results.
   *
   * @return $this
   */
  function addFallbackBuild(array $fallbackBuild) {
    $this->processors[] = new StaticBuildProvider($fallbackBuild);
    return $this;
  }

  /**
   * @param string $entity_type
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildMultiple($entity_type, array $entities) {
    $builds = parent::buildMultiple($entity_type, $entities);
    foreach ($this->processors as $processor) {
      if ($processor instanceof EntityBuildProcessorInterface) {
        $builds = $processor->processMultiple($builds, $entity_type, $entities);
      }
      elseif ($processor instanceof BuildProcessorInterface) {
        foreach ($builds as $delta => $build) {
          if (!empty($build)) {
            $builds[$delta] = $processor->process($build);
          }
        }
      }
      elseif ($processor instanceof BuildProviderInterface) {
        // This is a fallback build provider.
        $emptyDeltas = array();
        foreach ($entities as $delta => $entity) {
          if (empty($build)) {
            $emptyDeltas[] = $delta;
          }
        }
        if (!empty($emptyDeltas)) {
          $fallbackBuild = $processor->build();
          foreach ($emptyDeltas as $delta) {
            $builds[$delta] = $fallbackBuild;
          }
        }
      }
    }
    return $builds;
  }

}
