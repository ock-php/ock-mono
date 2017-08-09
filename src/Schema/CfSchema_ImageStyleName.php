<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Drupal\Component\Utility\Html;

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
    $styleLabelsRaw = image_style_options(FALSE);
    if (!isset($styleLabelsRaw[$styleName])) {
      return t('Unknown image style');
    }
    $styleLabelRaw = $styleLabelsRaw[$styleName];
    return Html::escape($styleLabelRaw);
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
    $styleLabelsRaw = image_style_options();
    return isset($styleLabelsRaw[$styleName]);
  }
}
