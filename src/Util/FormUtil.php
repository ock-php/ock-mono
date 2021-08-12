<?php

declare(strict_types=1);

namespace Drupal\cu\Util;

use Drupal\Core\Render\Element;

class FormUtil {

  /**
   * Gets a callback to find an element in a nested elements tree.
   *
   * @param string $property
   *   Property name.
   * @param mixed $value
   *   Expected value for the property.
   *
   * @return \Closure
   *   Callback to find an element in a nested elements tree.
   *   This is suitable for $element['#ajax']['callback'].
   */
  public static function f_mustFindNestedElement(string $property, $value): \Closure {
    return static function (array $form) use ($property, $value) {
      return self::mustFindNestedElement($form, $property, $value);
    };
  }

  /**
   * Finds an element in a nested elements tree.
   *
   * @param array $element
   *   Parent render or form element.
   * @param string $property
   *   Property name.
   * @param mixed $value
   *   Expected property value.
   *
   * @return array
   *   The element with the expected property value.
   *
   * @throws \Exception
   *   Element does not exist.
   */
  public static function mustFindNestedElement(array $element, string $property, $value): array {
    $candidate = self::findNestedElement($element, $property, $value);
    if ($candidate === NULL) {
      throw new \Exception('Element not found.');
    }
    return $candidate;
  }

  /**
   * Finds an element in a nested elements tree.
   *
   * @param array $element
   *   Parent render or form element.
   * @param string $property
   *   Property name.
   * @param mixed $value
   *   Expected property value.
   *
   * @return array|null
   *   The element with the expected property value, or NULL if not found.
   */
  public static function findNestedElement(array $element, $property, $value): ?array {
    if (isset($element[$property]) && $element[$property] === $value) {
      return $element;
    }
    foreach (Element::children($element) as $name) {
      $candidate = self::findNestedElement($element[$name], $property, $value);
      if ($candidate !== NULL) {
        return $candidate;
      }
    }
    return NULL;
  }

}
