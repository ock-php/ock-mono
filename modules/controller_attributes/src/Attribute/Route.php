<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes\Attribute;

use Symfony\Component\Routing\Route as RoutingRoute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Route implements RouteModifierInterface {

  public function __construct(
    private readonly string $path = '',
  ) {
    if ($path !== '') {
      if (str_ends_with($path, '/')) {
        throw new \RuntimeException("Path must not end with '/'. Found '$path'.");
      }
      if (!str_starts_with($path, '/')) {
        throw new \RuntimeException("Path must start with '/'. Found '$path'.");
      }
    }
  }

  public function modifyRoute(RoutingRoute $route, \ReflectionMethod|\ReflectionClass $reflector): void {
    $path = $route->getPath();
    if ($path === '/') {
      $path = '';
    }
    if ($path !== '') {
      if (str_ends_with($path, '/')) {
        throw new \RuntimeException("Pre-existing path must not end with '/'. Found '$path'.");
      }
      if (!str_starts_with($path, '/')) {
        throw new \RuntimeException("Pre-existing path must start with '/'. Found '$path'.");
      }
    }
    $route->setPath($route->getPath() . $this->path);
  }

}
