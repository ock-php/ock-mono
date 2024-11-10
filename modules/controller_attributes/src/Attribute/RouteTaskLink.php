<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes\Attribute;

use Symfony\Component\Routing\Route;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RouteTaskLink implements RouteModifierInterface {

  /**
   * Constructor.
   *
   * @param string $title
   * @param string|null $base_route
   */
  public function __construct(
    private readonly string $title,
    private readonly ?string $base_route = NULL,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function modifyRoute(Route $route, \ReflectionMethod|\ReflectionClass $reflector): void {
    $route->setOption('_task_link', $this->buildLink());
  }

  /**
   * @return array
   */
  protected function buildLink(): array {
    $link = [];
    $link['title'] = $this->title;
    if (NULL !== $this->base_route) {
      $link['base_route'] = $this->base_route;
    }
    return $link;
  }

}
