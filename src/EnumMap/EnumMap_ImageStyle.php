<?php

namespace Drupal\renderkit\EnumMap;

use Drupal\cfrapi\EnumMap\EnumMapInterface;

class EnumMap_ImageStyle implements EnumMapInterface {

  /**
   * @return mixed[]
   */
  function getSelectOptions() {
    return image_style_options();
  }

  /**
   * @param string $styleName
   *
   * @return string|null
   */
  function idGetLabel($styleName) {
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
  function idIsKnown($styleName) {
    if (empty($styleName)) {
      return TRUE;
    }
    $styles = image_styles();
    return isset($styles[$styleName]);
  }
}
