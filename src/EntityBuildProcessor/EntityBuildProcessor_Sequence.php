<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityBuildProcessor;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceWithContext;
use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;

/**
 * An entity build processor that runs each render array through a bunch of
 * processors.
 */
class EntityBuildProcessor_Sequence implements EntityBuildProcessorInterface {

  /**
   * @CfrPlugin(
   *   id = "sequence",
   *   label = "Sequence of processors"
   * )
   *
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface
   */
  public static function createCfrFormula(CfContextInterface $context = NULL): Formula_GroupValInterface {

    return Formula_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        Formula_IfaceWithContext::createSequence(
          EntityBuildProcessorInterface::class,
          $context),
      ],
      ['']);
  }

  /**
   * @param \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface[] $processors
   *
   * @return self
   */
  public static function create(array $processors): self {
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
