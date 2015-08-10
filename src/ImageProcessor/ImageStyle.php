<?php

namespace Drupal\renderkit\ImageProcessor;

class ImageStyle extends ImageProcessorBase {

  /**
   * @var string
   */
  private $styleName;

  /**
   * @param string $styleName
   *   The image style name.
   */
  function __construct($styleName) {
    $this->styleName = $styleName;
  }

  /**
   * @param array $build
   *   Render array with '#theme' => 'image'.
   *
   * @return array
   *   Render array after the processing.
   */
  function processImage(array $build) {
    if (empty($build)) {
      return array();
    }
    /* @see theme_image_style() */
    $build['#theme'] = 'image_style';
    $build['#style_name'] = $this->styleName;
    return $build;
  }
}
