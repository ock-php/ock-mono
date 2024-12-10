<?php
declare(strict_types=1);

namespace Drupal\controller_attributes;

/**
 * Url builder for routes based on controller attributes.
 */
class ClassRouteHelper extends ClassRouteHelperBase {

  /**
   * Static factory.
   *
   * @param class-string $class
   *   Controller class name.
   * @param array $routeParameters
   *   Route parameter values.
   * @param string $methodName
   *   Controller method name.
   *
   * @return self
   *   New instance.
   */
  public static function fromClassName(string $class, array $routeParameters, string $methodName): self {
    $routeBasename = RouteNameUtil::classNameGetRouteBasename($class);
    return new self(
      $routeBasename . '.',
      $routeParameters,
      $methodName,
    );
  }

  /**
   * Constructor.
   *
   * @param string $routePrefix
   *   Route name prefix, usually based on the class name.
   * @param array $routeParameters
   *   Parameters to use in the url path.
   * @param string $methodName
   *   Name of the controller method.
   */
  public function __construct(
    private $routePrefix,
    array $routeParameters,
    string $methodName,
  ) {
    parent::__construct($routeParameters, $methodName);
  }

  /**
   * {@inheritdoc}
   */
  public function routeName(): string {
    // @todo Instead this should rely on the methods on the class.
    return $this->routePrefix
      . RouteNameUtil::camelToSnake($this->getMethodName());
  }

}
