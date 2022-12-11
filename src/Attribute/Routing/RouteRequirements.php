<?php

namespace Drupal\ock\Attribute\Routing;

use Symfony\Component\Routing\Route;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RouteRequirements implements RouteModifierInterface {

  /**
   * Constructor.
   *
   * @param array $requirements
   */
  public function __construct(
    private readonly array $requirements,
  ) {}

  public function modifyRoute(Route $route, \ReflectionMethod|\ReflectionClass $reflector): void {
    $route->addRequirements($this->requirements);
  }

}
