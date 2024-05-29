<?php

declare(strict_types=1);

namespace Drupal\themekit\Util;

use Drupal\Core\Render\Element;

/**
 * Utility functions for render elements.
 */
class ElementUtil {

  /**
   * Extracts child render elements.
   *
   * @param array $element
   *   Render element to analyze.
   *
   * @return array<array-key, array>
   *   Child render elements with original keys.
   */
  public static function childElements(array $element): array {
    $keys = Element::children($element);
    return \array_map(
      static fn (string $key) => $element[$key],
      array_combine($keys, $keys),
    );
  }

  /**
   * Extracts child render elements.
   *
   * @param array $element
   *   Render element to analyze.
   *
   * @return list<array>
   *   Child render elements with flattened keys.
   */
  public static function childElementsList(array $element): array {
    $keys = Element::children($element);
    return \array_map(
      static fn (string $key) => $element[$key],
      $keys,
    );
  }

  /**
   * Extracts render element property values.
   *
   * @param array $element
   *   Render element to analyze.
   *
   * @return array<string, mixed>
   *   Property values.
   */
  public static function propertyValues(array $element): array {
    $keys = Element::properties($element);
    return \array_map(
      static fn (string $key) => $element[$key],
      array_combine($keys, $keys),
    );
  }

  /**
   * Extracts render element children and property values.
   *
   * @param array $element
   *   Render element to analyze.
   *
   * @return array{array<array-key, array>, array<string, mixed>}
   *   Child render elements and property values.
   */
  public static function childrenAndProperties(array $element): array {
    return [
      static::childElements($element),
      static::propertyValues($element),
    ];
  }

}
