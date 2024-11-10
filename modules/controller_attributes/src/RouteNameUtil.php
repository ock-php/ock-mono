<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes;

use Drupal\ock\Util\StringUtil;

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
      . StringUtil::camelCaseExplode(
        $method,
        TRUE,
        'AA Aa',
        '_',
      );
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
      $part = StringUtil::camelCaseExplode(
        $part,
        TRUE,
        'AA Bc',
        '_'
      );
      $part = \trim($part, '_');
    }
    return \implode('.', $parts);
  }

}
