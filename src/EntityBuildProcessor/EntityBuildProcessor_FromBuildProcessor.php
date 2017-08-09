<?php

namespace Drupal\renderkit8\EntityBuildProcessor;

use Drupal\renderkit8\BuildProcessor\BuildProcessorInterface;

class EntityBuildProcessor_FromBuildProcessor extends EntityBuildProcessorBase {

  /**
   * @var \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface
   */
  private $processor;

  /**
   * @CfrPlugin(
   *   id = "buildProcessor",
   *   label = @t("Build processor"),
   *   inline = true
   * )
   *
   * @param \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface $processor
   *
   * @return \Drupal\renderkit8\EntityBuildProcessor\EntityBuildProcessorInterface
   *
   * @todo Use identity / inline.
   */
  public static function create(BuildProcessorInterface $processor) {
    return new static($processor);
  }

  /**
   * BuildProcessorEntityBuildProcessor constructor.
   *
   * @param \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface $processor
   */
  public function __construct(BuildProcessorInterface $processor) {
    $this->processor = $processor;
  }

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   * @param string $entity_type
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *   Modified render array for the given entity.
   */
  public function processEntityBuild(array $build, $entity_type, $entity) {
    return $this->processor->process($build);
  }
}
