<?php
declare(strict_types=1);

namespace Drupal\renderkit\ImageProcessor;

use Drupal\renderkit\Formula\Formula_ImageStyleName;
use Ock\Ock\Attribute\Parameter\OckFormulaFromClass;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

#[OckPluginInstance('imageStyle', 'Image style')]
class ImageProcessor_ImageStyle implements ImageProcessorInterface {

  /**
   * @param string $styleName
   *   The image style name.
   */
  public function __construct(
    #[OckOption('image_style', 'Image style')]
    #[OckFormulaFromClass(Formula_ImageStyleName::class)]
    private readonly string $styleName,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function processImage(array $build): array {
    if (empty($build)) {
      return [];
    }
    /* @see theme_image_style() */
    $build['#theme'] = 'image_style';
    $build['#style_name'] = $this->styleName;
    return $build;
  }
}
