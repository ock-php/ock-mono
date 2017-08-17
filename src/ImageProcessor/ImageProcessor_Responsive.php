<?php

namespace Drupal\renderkit8\ImageProcessor;

use Drupal\image\Entity\ImageStyle;

/**
 * Responsive <img/> element with "srcset" and "sizes" attributes, based on
 * image styles.
 */
class ImageProcessor_Responsive implements ImageProcessorInterface {

  /**
   * The image style name to use for the src attribute itself.
   *
   * @var string|null
   */
  private $fallbackStyleName;

  /**
   * @var string[]
   */
  private $sizes = [];

  /**
   * The image style names.
   *
   * @var string[]
   *   Format: $[] = $imageStyleName
   */
  private $styleNames = [];

  /**
   * @param string|null $fallbackStyleName
   *   Image style for the fallback image (src attribute), for browsers that
   *   don't understand srcset.
   */
  public function __construct($fallbackStyleName = NULL) {
    $this->fallbackStyleName = $fallbackStyleName;
    $this->styleNames[] = $fallbackStyleName;
  }

  /**
   * @param int $min_width_px
   * @param string $formula
   *
   * @return $this
   */
  public function minWidthSize($min_width_px, $formula) {
    if ($min_width_px > 0) {
      $media_query = 'min-width: ' . $min_width_px . 'px';
      $formula = '(' . $media_query . ') ' . $formula;
    }
    $this->sizes[$min_width_px] = $formula;
    return $this;
  }

  /**
   * @param int $min_width_px
   * @param int|float $ratio
   * @param int|float $space
   *
   * @return $this
   */
  public function minWidthColumn($min_width_px, $ratio, $space) {
    $percentage = (100 * $ratio) . 'vw';
    $subtract = ($space * $ratio) . 'px';
    $formula = 'calc(' . $percentage . ' - ' . $subtract . ')';
    $this->minWidthSize($min_width_px, $formula);
    return $this;
  }

  /**
   * Adds a Drupal image style.
   *
   * @param string $style_name
   *
   * @return $this
   */
  public function addImageStyle($style_name) {
    $this->styleNames[] = $style_name;
    return $this;
  }

  /**
   * Processes an image render array.
   *
   * @param array $build
   *   Render array with '#theme' => 'image' and the original image path at
   *   #path. The original image path must be in a place that supports image
   *   style generation.
   *
   * @return array
   *   Render array after the processing.
   *
   * @todo Detect if the original image location does not support image styles.
   */
  public function processImage(array $build) {

    // Path to the original image file.
    $original_path = $build['#path'];
    $original_dimensions = $this->imageBuildGetDimensions($build);

    // Only change the '#path' and '#width' and '#height' if the fallback image
    // uses an image style.
    if (NULL !== $this->fallbackStyleName) {
      $fallbackStyle = ImageStyle::load($this->fallbackStyleName);
      /* @see theme_image_style() */
      $style_dimensions = $original_dimensions;
      $fallbackStyle->transformDimensions($style_dimensions, $original_path);
      $build['#path'] = $fallbackStyle->buildUri($original_path);
      $build['#width'] = $style_dimensions['width'];
      $build['#height'] = $style_dimensions['height'];
    }

    if (empty($this->sizes) || empty($this->styleNames)) {
      return $build;
    }

    /* @see theme_picture() */
    /* @see theme_image_srcset() */
    $srcset = [];
    foreach ($this->styleNames as $style_name) {
      if (empty($style_name)) {
        continue;
      }
      $style = ImageStyle::load($style_name);
      $style_src = $style->buildUrl($original_path);
      $style_dimensions = $original_dimensions;
      $style->transformDimensions($style_dimensions, $original_path);
      $srcset[] = $style_src . ' ' . $style_dimensions['width'] . 'w';
    }
    $build['#attributes']['srcset'] = implode(', ', $srcset);

    krsort($this->sizes);
    $build['#attributes']['sizes'] = empty($this->sizes)
      ? '100vw'
      : implode(', ', $this->sizes);

    return $build;
  }

  /**
   * @param array $build
   *   A render array with '#theme' => 'image'.
   *
   * @return int[]
   *   Format: array('width' => $width, 'height' => $height)
   * @throws \Exception
   */
  protected function imageBuildGetDimensions(array $build) {

    if (!empty($build['#width']) && !empty($build['#height'])) {
      return [
        'width' => $build['#width'],
        'height' => $build['#height'],
      ];
    }

    throw new \Exception("Image lacks width/height information.");
  }

}
