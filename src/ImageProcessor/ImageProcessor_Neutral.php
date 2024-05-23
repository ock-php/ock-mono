<?php
declare(strict_types=1);

namespace Drupal\renderkit\ImageProcessor;

use Donquixote\Ock\Attribute\Plugin\OckPluginInstance;

#[OckPluginInstance('original', 'Original image')]
class ImageProcessor_Neutral implements ImageProcessorInterface {

  /**
   * @param array $build
   *   Render array with '#theme' => 'image'.
   *
   * @return array
   *   Render array after the processing.
   */
  public function processImage(array $build): array {
    return $build;
  }
}
