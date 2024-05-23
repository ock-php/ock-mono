<?php
declare(strict_types=1);

namespace Drupal\renderkit\ImageProcessor;

/**
 * Render array processor that only processes render arrays with
 * '#theme' => 'image'.
 *
 * The resulting render array could be e.g. the same image with
 * '#theme' => 'image_style'.
 */
interface ImageProcessorInterface {

  /**
   * @param array $build
   *   Render array with '#theme' => 'image'.
   *
   * @return array
   *   Render array after the processing.
   */
  public function processImage(array $build): array;

}
