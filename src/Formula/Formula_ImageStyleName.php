<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Select\Formula_Select_BufferedBase;
use Drupal\ock\DrupalText;

class Formula_ImageStyleName extends Formula_Select_BufferedBase {

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$map, array &$labels, array &$groupLabels): void {
    $options = image_style_options();
    $map = array_fill_keys(array_keys($options), '');
    $labels = DrupalText::multiple($options);
  }

}
