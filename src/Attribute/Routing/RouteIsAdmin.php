<?php

namespace Drupal\ock\Attribute\Routing;

use Symfony\Component\Routing\Route;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RouteIsAdmin implements RouteModifierInterface {

  /**
   * {@inheritdoc}
   */
  public function modifyRoute(Route $route, \ReflectionMethod|\ReflectionClass $reflector): void {
    $route->setOption('_admin_route', TRUE);
  }

}
