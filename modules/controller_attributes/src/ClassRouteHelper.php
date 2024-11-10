<?php
declare(strict_types=1);

namespace Drupal\controller_attributes;

class ClassRouteHelper extends ClassRouteHelperBase {

  /**
   * @param string $class
   * @param array $routeParameters
   * @param string $methodName
   *
   * @return self
   */
  public static function fromClassName(string $class, array $routeParameters, string $methodName): self {
    $routeBasename = RouteNameUtil::classNameGetRouteBasename($class);
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
  public function __construct(private $routePrefix, array $routeParameters, string $methodName) {
    parent::__construct($routeParameters, $methodName);
  }

  /**
   * @return string
   */
  public function routeName(): string {
    // @todo Instead this should rely on the methods on the class.
    return $this->routePrefix
      . RouteNameUtil::camelToSnake($this->getMethodName());
  }

}
