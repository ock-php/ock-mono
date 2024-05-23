<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityBuildProcessor;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;
use Ock\Ock\Attribute\Parameter\OckListOfObjects;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * An entity build processor that runs each render array through a bunch of
 * processors.
 */
class EntityBuildProcessor_Sequence implements EntityBuildProcessorInterface {

  /**
   * @param \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface[] $processors
   *
   * @return self
   */
  #[OckPluginInstance('sequence', 'Sequence of processors')]
  public static function create(
    #[OckOption('processors', 'Processors')]
    #[OckListOfObjects(EntityBuildProcessorInterface::class)]
    array $processors,
  ): self {
    $sequence = new self();
    foreach ($processors as $processor) {
      if ($processor instanceof EntityBuildProcessorInterface) {
        $sequence->addEntityBuildProcessor($processor);
      }
      elseif ($processor instanceof BuildProcessorInterface) {
        $sequence->addBuildProcessor($processor);
      }
    }
    return $sequence;
  }

  /**
   * @var object[]
   */
  private array $processors = [];

  /**
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $buildProcessor
   *
   * @return $this
   */
  public function addBuildProcessor(BuildProcessorInterface $buildProcessor): self {
    $this->processors[] = $buildProcessor;
    return $this;
  }

  /**
   * @param \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface $entityBuildProcessor
   *
   * @return $this
   */
  public function addEntityBuildProcessor(EntityBuildProcessorInterface $entityBuildProcessor): self {
    $this->processors[] = $entityBuildProcessor;
    return $this;
  }

  /**
   * Processes all the render arrays, by passing them through all the registered
   * processors.
   *
   * @param array $build
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array[]
   *   Modified render arrays for the given entities.
   */
  public function processEntityBuild(array $build, EntityInterface $entity): array {

    foreach ($this->processors as $processor) {
      if (empty($build)) {
        return $build;
      }
      elseif ($processor instanceof EntityBuildProcessorInterface) {
        $build = $processor->processEntityBuild($build, $entity);
      }
      elseif ($processor instanceof BuildProcessorInterface) {
        $build = $processor->process($build);
      }
    }

    return $build;
  }

}
