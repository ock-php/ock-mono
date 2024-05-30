<?php
declare(strict_types=1);

namespace Drupal\themekit\Util;

use Drupal\Core\Template\Attribute;

final class HtmlUtil {

  /**
   * @param array $element
   * @param string $key
   *
   * @return string
   */
  public static function elementAttributesString(array $element, string $key = '#attributes'): string {

    if (!isset($element[$key])) {
      return '';
    }

    if (\is_array($element[$key])) {
      $attributes_object = new Attribute($element[$key]);
      return $attributes_object->__toString();
    }

    if ($element[$key] instanceof Attribute) {
      return $element[$key]->__toString();
    }

    $type = \gettype($key);

    throw new \RuntimeException("Unexpected value ($type) for \$element[$key].");
  }

  /**
   * @param array $attributes
   *
   * @return \Drupal\Core\Template\Attribute
   */
  public static function attributes(array $attributes): Attribute {
    return new Attribute($attributes);
  }

}
