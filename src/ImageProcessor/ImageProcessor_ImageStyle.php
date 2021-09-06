<?php
declare(strict_types=1);

namespace Drupal\renderkit\ImageProcessor;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal_Callback;
use Drupal\renderkit\Formula\Formula_ImageStyleName;

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
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function createFormula(): FormulaInterface {

    return Formula_GroupVal_Callback::fromClass(
      __CLASS__,
      [
        new Formula_ImageStyleName(),
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
