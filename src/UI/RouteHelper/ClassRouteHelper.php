<?php
declare(strict_types=1);

namespace Drupal\ock\UI\RouteHelper;

use Drupal\ock\Util\StringUtil;

class ClassRouteHelper extends ClassRouteHelperBase {

  /**
   * @param string $class
   * @param array $routeParameters
   * @param string $methodName
   *
   * @return self
   */
  public static function fromClassName($class, array $routeParameters, $methodName): self {
    $routeBasename = StringUtil::classNameGetRouteBasename($class);
    return new self(
      $routeBasename . '.',
      $routeParameters,
      $methodName);
  }

  /**
   * @param string $routePrefix
   * @param array $routeParameters
   * @param string $methodName
   */
  public function __construct(private $routePrefix, array $routeParameters, $methodName) {
    parent::__construct($routeParameters, $methodName);
  }

  /**
   * @return string
   */
  public function routeName(): string {

    // @todo Instead this should rely on the methods on the class.
    return $this->routePrefix . StringUtil::camelCaseExplode(
      $this->getMethodName(),
      TRUE,
      'AA Aa',
      '_');
  }
}
