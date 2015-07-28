<?php

namespace Drupal\renderkit\ImageDerivative;

/**
 * Render array processor that only processes render arrays with
 * '#theme' => 'image'.
 *
 * The resulting render array could be e.g. the same image with
 * '#theme' => 'image_style'.
 */
interface ImageDerivativeInterface {

  /**
   * @param array $build
   *   Render array with '#theme' => 'image'.
   *
   * @return array
   *   Render array after the processing.
   */
  function processImage(array $build);

}
