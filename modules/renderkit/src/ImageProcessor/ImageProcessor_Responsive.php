<?php
declare(strict_types=1);

namespace Drupal\renderkit\ImageProcessor;

use Drupal\image\Entity\ImageStyle;

/**
 * Responsive <img/> element with "srcset" and "sizes" attributes, based on
 * image styles.
 */
class ImageProcessor_Responsive implements ImageProcessorInterface {

  /**
   * @var string[]
   */
  private array $sizes = [];

  /**
   * The image style names.
   *
   * @var string[]
   *   Format: $[] = $imageStyleName
   */
  private array $styleNames = [];

  /**
   * @param string|null $fallbackStyleName
   *   Image style for the fallback image (src attribute), for browsers that
   *   don't understand srcset.
   */
  public function __construct(
    private readonly ?string $fallbackStyleName = NULL,
  ) {
    // @todo Load the image styles here, instead of later!
    $this->styleNames[] = $fallbackStyleName;
  }

  /**
   * @param int $min_width_px
   * @param string $formula
   *
   * @return $this
   */
  public function minWidthSize(int $min_width_px, string $formula): self {
    if ($min_width_px > 0) {
      $media_query = 'min-width: ' . $min_width_px . 'px';
      $formula = '(' . $media_query . ') ' . $formula;
    }
    $this->sizes[$min_width_px] = $formula;
    return $this;
  }

  /**
   * @param int $min_width_px
   * @param float|int $ratio
   * @param float|int $space
   *
   * @return $this
   */
  public function minWidthColumn(int $min_width_px, float|int $ratio, float|int $space): self {
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
  public function addImageStyle(string $style_name): self {
    $this->styleNames[] = $style_name;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function processImage(array $build): array {

    // Path to the original image file.
    $original_path = $build['#path'];
    $original_dimensions = $this->imageBuildGetDimensions($build);

    // Only change the '#path' and '#width' and '#height' if the fallback image
    // uses an image style.
    if (NULL !== $this->fallbackStyleName) {
      $fallbackStyle = ImageStyle::load($this->fallbackStyleName);
      // @todo Log if style not found.
      if (null !== $fallbackStyle) {
        /* @see theme_image_style() */
        $style_dimensions = $original_dimensions;
        $fallbackStyle->transformDimensions($style_dimensions, $original_path);
        $build['#path'] = $fallbackStyle->buildUri($original_path);
        $build['#width'] = $style_dimensions['width'];
        $build['#height'] = $style_dimensions['height'];
      }
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
      if (null === $style) {
        // @todo Log this.
        continue;
      }
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
   * @return int[]|null[]
   *   Format: ['width' => $widthOrNull, 'height' => $heightOrNull]
   */
  protected function imageBuildGetDimensions(array $build): array {

    return [
      'width' => ($build['#width'] ?? null) ?: null,
      'height' => ($build['#height'] ?? null) ?: null,
    ];
  }

}
