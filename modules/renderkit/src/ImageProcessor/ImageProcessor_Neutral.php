<?php
declare(strict_types=1);

namespace Drupal\renderkit\ImageProcessor;

use Ock\Ock\Attribute\Plugin\OckPluginInstance;

#[OckPluginInstance('original', 'Original image')]
class ImageProcessor_Neutral implements ImageProcessorInterface {

  /**
   * {@inheritdoc}
   */
  public function processImage(array $build): array {
    return $build;
  }
}
