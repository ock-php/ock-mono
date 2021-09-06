<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityBuildProcessor;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;

class EntityBuildProcessor_FromBuildProcessor implements EntityBuildProcessorInterface {

  /**
   * @var \Drupal\renderkit\BuildProcessor\BuildProcessorInterface
   */
  private $processor;

  /**
   * @CfrPlugin(
   *   id = "buildProcessor",
   *   label = @t("Build processor"),
   *   inline = true
   * )
   *
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $processor
   *
   * @return \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface
   *
   * @todo Use identity / inline.
   */
  public static function create(BuildProcessorInterface $processor) {
    return new static($processor);
  }

  /**
   * BuildProcessorEntityBuildProcessor constructor.
   *
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $processor
   */
  public function __construct(BuildProcessorInterface $processor) {
    $this->processor = $processor;
  }

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *   Modified render array for the given entity.
   */
  public function processEntityBuild(array $build, EntityInterface $entity): array {
    return $this->processor->process($build);
  }
}
