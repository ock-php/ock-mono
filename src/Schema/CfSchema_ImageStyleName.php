<?php

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;

class CfSchema_ImageStyleName implements CfSchema_OptionsInterface {

  /**
   * @return string[][]
   */
  public function getGroupedOptions() {
    return ['' => image_style_options()];
  }

  /**
   * @param string $styleName
   *
   * @return string|null
   */
  public function idGetLabel($styleName) {
    if (empty($styleName)) {
      return '- ' . t('Original image') . ' -';
    }
    $styleLabelsRaw = image_style_options(FALSE, PASS_THROUGH);
    if (!isset($styleLabelsRaw[$styleName])) {
      return t('Unknown image style');
    }
    $styleLabelRaw = $styleLabelsRaw[$styleName];
    return check_plain($styleLabelRaw);
  }

  /**
   * @param string $styleName
   *
   * @return bool
   */
  public function idIsKnown($styleName) {
    if (empty($styleName)) {
      return TRUE;
    }
    $styles = image_styles();
    return isset($styles[$styleName]);
  }
}
