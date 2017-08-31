<?php

namespace Drupal\themekit\Util;

use Drupal\Core\Template\Attribute;

final class HtmlUtil {

  /**
   * @param array $attributes
   *
   * @return \Drupal\Core\Template\Attribute
   */
  public static function attributes(array $attributes) {
    return new Attribute($attributes);
  }

}
