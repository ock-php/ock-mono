<?php
declare(strict_types=1);

namespace Drupal\renderkit\ImageProcessor;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;
use Ock\Ock\Attribute\Parameter\OckDecorated;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

#[OckPluginInstance("buildProcessor", "Build processor")]
class ImageProcessor_BuildProcessor implements ImageProcessorInterface {

  /**
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface|null $buildProcessor
   * @param \Drupal\renderkit\ImageProcessor\ImageProcessorInterface|null $decorated
   */
  public function __construct(
    #[OckOption('buildProcessor', 'Build processor')]
    private readonly ?BuildProcessorInterface $buildProcessor = NULL,
    #[OckDecorated]
    private readonly ?ImageProcessorInterface $decorated = NULL,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function processImage(array $build): array {
    if (NULL !== $this->decorated) {
      $build = $this->decorated->processImage($build);
    }
    if (NULL !== $this->buildProcessor) {
      $build = $this->buildProcessor->process($build);
    }
    return $build;
  }
}
