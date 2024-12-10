<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes;

/**
 * Static methods for automatic route naming.
 *
 * Having these be static makes them usable in static methods to create url
 * objects.
 */
class RouteNameUtil {

  /**
   * Creates a canonical route name for a controller method.
   *
   * @param callable&array{string, string} $class_and_method
   *   Controller class name and method name.
   *
   * @return string
   *   Automatic route name.
   */
  public static function methodGetRouteName(array $class_and_method): string {
    [$class, $method] = $class_and_method;
    return self::classNameGetRouteBasename($class)
      . '.'
      . self::camelToSnake($method);
  }

  /**
   * Creates a route name part for a given class name.
   *
   * @param class-string $class
   *   Controller class name.
   *
   * @return string
   *   Automatic route name part.
   */
  public static function classNameGetRouteBasename(string $class): string {
    $parts = \explode('\\', $class);
    $lastpart = \array_pop($parts);
    $parts = \array_diff($parts, ['Drupal', 'UI', 'Controller']);
    $parts[] = \preg_replace(
      [
        '@^Controller_(.+)$@',
        '@^(.+)Controller$@',
      ],
      [
        '\\1',
        '\\1',
      ],
      $lastpart
    );
    foreach ($parts as &$part) {
      $part = self::camelToSnake($part);
      $part = \trim($part, '_');
    }
    return \implode('.', $parts);
  }

  /**
   * Converts CamelCase to snake_case.
   *
   * @param string $string
   *   A name in camel case.
   *
   * @return string
   *   The name converted to snake case.
   */
  public static function camelToSnake(string $string): string {
    return strtolower(preg_replace(
      '#(?<=[^_])(?=[A-Z][a-z])|(?<=[a-z])(?=[A-Z])#',
      '_',
      $string,
    ));
  }

}
