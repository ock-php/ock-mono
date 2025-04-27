<?php

declare(strict_types = 1);

namespace Drupal\controller_attributes\Attribute;

use Symfony\Component\Routing\Route;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RouteMenuLink implements RouteModifierInterface {

  /**
   * @var array
   */
  private array $link;

  /**
   * Constructor.
   *
   * @param string|null $title
   * @param string|null $description
   * @param string|null $parent
   * @param string|null $menu_name
   */
  public function __construct(
    string $title = NULL,
    string $description = NULL,
    string $parent = NULL,
    string $menu_name = NULL,
  ) {
    $this->link = array_filter([
      'title' => $title,
      'description' => $description,
      'parent' => $parent,
      'menu_name' => $menu_name,
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function modifyRoute(Route $route, \ReflectionMethod|\ReflectionClass $reflector): void {
    $route->setOption('_menu_link', $this->link);
  }

}
