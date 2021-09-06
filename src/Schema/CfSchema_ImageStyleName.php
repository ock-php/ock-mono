<?php
declare(strict_types=1);

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Schema\Select\CfSchema_SelectInterface;
use Drupal\Component\Utility\Html;

class CfSchema_ImageStyleName implements CfSchema_SelectInterface {

  /**
   * @return string[][]
   */
  public function getGroupedOptions(): array {
    return ['' => image_style_options()];
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($styleName) {
    if (empty($styleName)) {
      return '- ' . t('Original image') . ' -';
    }
    $styleLabelsRaw = image_style_options(FALSE);
    if (!isset($styleLabelsRaw[$styleName])) {
      return (string)t('Unknown image style');
    }
    $styleLabelRaw = $styleLabelsRaw[$styleName];
    return Html::escape($styleLabelRaw);
  }

  /**
   * @param string $styleName
   *
   * @return bool
   */
  public function idIsKnown($styleName): bool {
    if (empty($styleName)) {
      return TRUE;
    }
    $styleLabelsRaw = image_style_options();
    return isset($styleLabelsRaw[$styleName]);
  }
}
