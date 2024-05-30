<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityBuildProcessor;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

class EntityBuildProcessor_FromBuildProcessor implements EntityBuildProcessorInterface {

  /**
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $processor
   *
   * @return \Drupal\renderkit\EntityBuildProcessor\EntityBuildProcessorInterface
   *
   * @todo Mark this as adapter / inline.
   */
  #[OckPluginInstance('buildProcessor', 'Build processor')]
  public static function create(BuildProcessorInterface $processor): EntityBuildProcessorInterface {
    return new static($processor);
  }

  /**
   * BuildProcessorEntityBuildProcessor constructor.
   *
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface $processor
   */
  public function __construct(
    private readonly BuildProcessorInterface $processor,
  ) {}

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
