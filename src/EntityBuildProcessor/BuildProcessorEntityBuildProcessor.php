<?php

namespace Drupal\renderkit\EntityBuildProcessor;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;

class BuildProcessorEntityBuildProcessor extends EntityBuildProcessorBase {

  /**
   * @var \Drupal\renderkit\BuildProcessor\BuildProcessorInterface
   */
  private $processor;

  /**
   * @UniPlugin(
   *   id = "build_processor",
   *   label = @Translate("Build processor")
   * )
   *
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $processor
   *
   * @return \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface
   */
  static function create(BuildProcessorInterface $processor) {
    return new static($processor);
  }

  /**
   * BuildProcessorEntityBuildProcessor constructor.
   *
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $processor
   */
  function __construct(BuildProcessorInterface $processor) {
    $this->processor = $processor;
  }

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Modified render array for the given entity.
   */
  function processEntityBuild(array $build, $entity_type, $entity) {
    return $this->processor->process($build);
  }
}
