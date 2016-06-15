<?php

namespace Drupal\renderkit\ImageProcessor;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;

/**
 * @CfrPlugin("buildProcessor", "Build processor")
 */
class ImageProcessor_BuildProcessor implements ImageProcessorInterface {

  /**
   * @var \Drupal\renderkit\BuildProcessor\BuildProcessorInterface|null
   */
  private $buildProcessor;

  /**
   * @var \Drupal\renderkit\ImageProcessor\ImageProcessorInterface|null
   */
  private $decorated;

  /**
   * @param \Drupal\renderkit\BuildProcessor\BuildProcessorInterface|null $buildProcessor
   * @param \Drupal\renderkit\ImageProcessor\ImageProcessorInterface|null $decoratedImageProcessor
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
  function processImage(array $build) {
    if (NULL !== $this->decorated) {
      $build = $this->decorated->processImage($build);
    }
    if (NULL !== $this->buildProcessor) {
      $build = $this->buildProcessor->process($build);
    }
    return $build;
  }
}
