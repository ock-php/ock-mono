<?php

namespace Drupal\ock\Attribute\Routing;

use Symfony\Component\Routing\Route;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RouteRequirePermission implements RouteModifierInterface {

  /**
   * Constructor.
   *
   * @param string $permissionName
   */
  public function __construct(
    private readonly string $permissionName,
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public function modifyRoute(Route $route, \ReflectionMethod|\ReflectionClass $reflector): void {
    $route->addRequirements(['_permission' => $this->permissionName]);
  }

}
