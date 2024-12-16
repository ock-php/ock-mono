<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes\Attribute;

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
