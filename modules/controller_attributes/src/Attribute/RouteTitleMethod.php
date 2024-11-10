<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes\Attribute;

use Symfony\Component\Routing\Route as RoutingRoute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RouteTitleMethod implements RouteModifierInterface {

  /**
   * Constructor.
   *
   * @param callable&array{string, string} $method
   *   Format: [$class, $method].
   */
  public function __construct(
    private readonly array $method,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function modifyRoute(RoutingRoute $route, \ReflectionMethod|\ReflectionClass $reflector): void {
    $route->setDefault('_title_callback', $this->method[0] . '::' . $this->method[1]);
  }

}
