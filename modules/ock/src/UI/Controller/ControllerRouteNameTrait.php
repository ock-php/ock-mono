<?php

declare(strict_types = 1);

namespace Drupal\ock\UI\Controller;

use Drupal\ock\Util\StringUtil;

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
    return StringUtil::methodGetRouteName([static::class, $methodName]);
  }

  /**
   * @return string
   */
  public static function getRouteBaseName(): string {
    return StringUtil::classNameGetRouteBasename(static::class);
  }

}
