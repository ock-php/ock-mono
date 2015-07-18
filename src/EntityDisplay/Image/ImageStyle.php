<?php

namespace Drupal\renderkit\EntityDisplay\Image;

use Drupal\renderkit\EntityDisplay\Image\ImageDerivativeBase;
use Drupal\renderkit\EntityImage\EntityImageInterface;

class ImageStyle extends ImageDerivativeBase {

  /**
   * @var string
   */
  private $styleName;

  /**
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $imageProvider
   *   Object that extracts an image path from an entity, e.g. based on an image
   *   field, or the user picture, etc.
   * @param string $styleName
   *   The image style name.
   */
  function __construct(EntityImageInterface $imageProvider, $styleName) {
    parent::__construct($imageProvider);
    $this->styleName = $styleName;
  }

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   *   Contains '#theme' => 'image'.
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Modified render array for the given entity.
   */
  protected function decorateImage($build, $entity_type, $entity) {
    if (empty($build)) {
      return array();
    }
    /* @see theme_image_style() */
    $build['#theme'] = 'image_style';
    $build['#style_name'] = $this->styleName;
    return $build;
  }
}
