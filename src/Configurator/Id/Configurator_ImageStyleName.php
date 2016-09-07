<?php

namespace Drupal\renderkit\Configurator\Id;

use Drupal\cfrapi\Configurator\Id\Configurator_SelectBase;

class Configurator_ImageStyleName extends Configurator_SelectBase {

  /**
   * @return mixed[]
   */
  protected function getSelectOptions() {
    return image_style_options();
  }

  /**
   * @param string $styleName
   *
   * @return string|null
   */
  protected function idGetLabel($styleName) {
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
  protected function idIsKnown($styleName) {
    if (empty($styleName)) {
      return TRUE;
    }
    $styles = image_styles();
    return isset($styles[$styleName]);
  }
}
