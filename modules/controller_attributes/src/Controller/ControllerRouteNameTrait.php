<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes\Controller;

use Drupal\controller_attributes\RouteNameUtil;

trait ControllerRouteNameTrait {

  /**
   * @param \ReflectionMethod $method
   *
   * @return string|null
   */
  public static function methodGetRouteName(\ReflectionMethod $method): ?string {
    return static::methodNameGetRouteName($method->name);
  }

  /**
   * @param string $methodName
   *
   * @return string
   */
  public static function methodNameGetRouteName(string $methodName): string {
    return RouteNameUtil::methodGetRouteName([static::class, $methodName]);
  }

  /**
   * @return string
   */
  public static function getRouteBaseName(): string {
    return RouteNameUtil::classNameGetRouteBasename(static::class);
  }

}
