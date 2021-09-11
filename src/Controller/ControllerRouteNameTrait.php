<?php

namespace Drupal\ock\Controller;

use Drupal\controller_annotations\Util\StringUtil;

trait ControllerRouteNameTrait {

  /**
   * @param \ReflectionMethod $method
   *
   * @return string|null
   *
   * @see \Drupal\controller_annotations\Controller\ControllerRouteNameInterface::methodGetRouteName()
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
    return static::getRouteBaseName()
      . '.'
      . StringUtil::camelCaseExplode(
        $methodName,
        true,
        'AA Aa',
        '_');
  }

  /**
   * @return string
   */
  public static function getRouteBaseName(): string {
    return StringUtil::classNameGetRouteBasename(static::class);
  }
}
