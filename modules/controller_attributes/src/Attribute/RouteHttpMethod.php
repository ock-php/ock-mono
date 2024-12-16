<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes\Attribute;

use Symfony\Component\Routing\Route;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RouteHttpMethod implements RouteModifierInterface {

  /**
   * @var string[]
   */
  private array $methods;

  /**
   * Constructor.
   *
   * @param string ...$methods
   *   E.g. 'GET', 'POST' etc.
   */
  public function __construct(string ...$methods) {
    $this->methods = $methods;
  }

  /**
   * {@inheritdoc}
   */
  public function modifyRoute(Route $route, \ReflectionMethod|\ReflectionClass $reflector): void {
    $route->setMethods($this->methods);
  }

}
