<?php

namespace Drupal\ock\Attribute\Routing;

use Symfony\Component\Routing\Route;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RouteDefaults implements RouteModifierInterface {

  /**
   * @param array $defaults
   */
  public function __construct(
    private readonly array $defaults,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function modifyRoute(Route $route, \ReflectionMethod|\ReflectionClass $reflector): void {
    $route->addDefaults($this->defaults);
  }

}
