<?php

namespace Drupal\renderkit\ImageProcessor;

use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Drupal\renderkit\Configurator\Id\Configurator_ImageStyleName;

class ImageProcessor_ImageStyle implements ImageProcessorInterface {

  /**
   * @var string
   */
  private $styleName;

  /**
   * @CfrPlugin(
   *   id = "imageStyle",
   *   label = @t("Image style")
   * )
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createConfigurator() {
    $paramConfigurators = [new Configurator_ImageStyleName()];
    $labels = [t('Image style')];
    return Configurator_CallbackConfigurable::createFromClassName(__CLASS__, $paramConfigurators, $labels);
  }

  /**
   * @param string $styleName
   *   The image style name.
   */
  public function __construct($styleName) {
    $this->styleName = $styleName;
  }

  /**
   * @param array $build
   *   Render array with '#theme' => 'image'.
   *
   * @return array
   *   Render array after the processing.
   */
  public function processImage(array $build) {
    if (empty($build)) {
      return [];
    }
    /* @see theme_image_style() */
    $build['#theme'] = 'image_style';
    $build['#style_name'] = $this->styleName;
    return $build;
  }
}
