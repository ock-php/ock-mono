<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Select\Formula_Select_BufferedBase;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;
use Drupal\ock\DrupalText;

class Formula_ImageStyleName extends Formula_Select_BufferedBase {

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$grouped_options, array &$group_labels): void {
    $grouped_options[''] = DrupalText::multiple(image_style_options());
  }

  /**
   * @return string[][]
   */
  public function getGroupedOptions(): array {
    return ['' => image_style_options()];
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    if (!$id) {
      return Text::t('Original image')
        ->wrapSprintf('- %s -');
    }
    $label = image_style_options(FALSE)[$id] ?? NULL;
    if ($label === NULL) {
      return NULL;
    }
    return DrupalText::fromVarOr($label, $id);
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id): bool {
    if (empty($id)) {
      return TRUE;
    }
    $styleLabelsRaw = image_style_options();
    return isset($styleLabelsRaw[$id]);
  }

}
