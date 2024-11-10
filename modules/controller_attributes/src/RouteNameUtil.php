<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes;

class RouteNameUtil {

  /**
   * @param callable&array{string, string} $class_and_method
   *
   * @return string
   */
  public static function methodGetRouteName(array $class_and_method): string {
    [$class, $method] = $class_and_method;
    return self::classNameGetRouteBasename($class)
      . '.'
      . self::camelToSnake($method);
  }

  /**
   * @param class-string $class
   *
   * @return string
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

  public static function camelToSnake(string $string): string {
    return strtolower(preg_replace(
      '#(?<=[^_])(?=[A-Z][a-z])|(?<=[a-z])(?=[A-Z])#',
      '_',
      $string,
    ));
  }

}
