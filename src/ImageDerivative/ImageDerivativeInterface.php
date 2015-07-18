<?php

namespace Drupal\renderkit\ImageDerivative;

use Drupal\renderkit\BuildProcessor\BuildProcessorInterface;

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
