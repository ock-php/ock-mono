<?php

namespace Drupal\renderkit8\EntityBuildProcessor;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Drupal\renderkit8\BuildProcessor\BuildProcessorInterface;

/**
 * An entity build processor that runs each render array through a bunch of
 * processors.
 */
class EntityBuildProcessor_Sequence implements EntityBuildProcessorInterface {

  use EntitiesBuildsProcessorTrait;

  /**
   * @CfrPlugin(
   *   id = "sequence",
   *   label = "Sequence of processors"
   * )
   *
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
   */
  public static function createCfrSchema(CfContextInterface $context = NULL) {

    return CfSchema_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        CfSchema_IfaceWithContext::createSequence(
          EntityBuildProcessorInterface::class,
          $context),
      ],
      ['']);
  }

  /**
   * @param \Drupal\renderkit8\EntityBuildProcessor\EntityBuildProcessorInterface[] $processors
   *
   * @return self
   */
  public static function create(array $processors) {
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
  private $processors = [];

  /**
   * @param \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface $buildProcessor
   *
   * @return $this
   */
  public function addBuildProcessor(BuildProcessorInterface $buildProcessor) {
    $this->processors[] = $buildProcessor;
    return $this;
  }

  /**
   * @param \Drupal\renderkit8\EntityBuildProcessor\EntityBuildProcessorInterface $entityBuildProcessor
   *
   * @return $this
   */
  public function addEntityBuildProcessor(EntityBuildProcessorInterface $entityBuildProcessor) {
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
  public function processEntitiesBuilds(array $builds, $entity_type, array $entities) {
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
