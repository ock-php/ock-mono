<?php
declare(strict_types=1);

namespace Drupal\renderkit8\ImageProcessor;

use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Drupal\renderkit8\Schema\CfSchema_ImageStyleName;

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
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createSchema() {

    return CfSchema_GroupVal_Callback::fromClass(
      __CLASS__,
      [
        new CfSchema_ImageStyleName(),
      ],
      [
        t('Image style'),
      ]);
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
