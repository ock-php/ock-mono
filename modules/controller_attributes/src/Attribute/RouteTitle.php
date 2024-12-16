<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes\Attribute;

use Symfony\Component\Routing\Route;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RouteTitle implements RouteModifierInterface {

  /**
   * Constructor.
   *
   * @param string $title
   */
  public function __construct(
    private readonly string $title,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function modifyRoute(Route $route, \ReflectionMethod|\ReflectionClass $reflector): void {
    $route->addDefaults(['_title' => $this->title]);
  }

}
