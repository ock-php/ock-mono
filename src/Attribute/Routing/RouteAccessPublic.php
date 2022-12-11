<?php

namespace Drupal\ock\Attribute\Routing;

use Symfony\Component\Routing\Route;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RouteAccessPublic implements RouteModifierInterface {

  public function modifyRoute(Route $route, \ReflectionMethod|\ReflectionClass $reflector): void {
    $route->addRequirements(['_access' => 'TRUE']);
  }

}
