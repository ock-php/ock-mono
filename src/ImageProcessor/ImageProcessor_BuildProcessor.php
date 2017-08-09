<?php

namespace Drupal\renderkit8\ImageProcessor;

use Drupal\renderkit8\BuildProcessor\BuildProcessorInterface;

/**
 * @CfrPlugin("buildProcessor", "Build processor")
 */
class ImageProcessor_BuildProcessor implements ImageProcessorInterface {

  /**
   * @var \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface|null
   */
  private $buildProcessor;

  /**
   * @var \Drupal\renderkit8\ImageProcessor\ImageProcessorInterface|null
   */
  private $decorated;

  /**
   * @param \Drupal\renderkit8\BuildProcessor\BuildProcessorInterface|null $buildProcessor
   * @param \Drupal\renderkit8\ImageProcessor\ImageProcessorInterface|null $decoratedImageProcessor
   */
  public function __construct(BuildProcessorInterface $buildProcessor = NULL, ImageProcessorInterface $decoratedImageProcessor = NULL) {
    $this->buildProcessor = $buildProcessor;
    $this->decorated = $decoratedImageProcessor;
  }

  /**
   * @param array $build
   *   Render array with '#theme' => 'image'.
   *
   * @return array
   *   Render array after the processing.
   */
  public function processImage(array $build) {
    if (NULL !== $this->decorated) {
      $build = $this->decorated->processImage($build);
    }
    if (NULL !== $this->buildProcessor) {
      $build = $this->buildProcessor->process($build);
    }
    return $build;
  }
}
