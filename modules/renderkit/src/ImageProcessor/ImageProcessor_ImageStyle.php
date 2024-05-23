<?php
declare(strict_types=1);

namespace Drupal\renderkit\ImageProcessor;

use Donquixote\Ock\Attribute\Parameter\OckFormulaFromClass;
use Donquixote\Ock\Attribute\Parameter\OckOption;
use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;
use Drupal\renderkit\Formula\Formula_ImageStyleName;

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
   * @param array $build
   *   Render array with '#theme' => 'image'.
   *
   * @return array
   *   Render array after the processing.
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
