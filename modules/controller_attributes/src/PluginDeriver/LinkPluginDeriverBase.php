<?php
declare(strict_types=1);

namespace Drupal\controller_attributes\PluginDeriver;

use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\Routing\RouteProviderInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Route;

abstract class LinkPluginDeriverBase extends PluginDeriverBase implements ContainerDeriverInterface {

  public function __construct(
    protected RouteProviderInterface $provider,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id): static {
    return new static(
      $container->get(RouteProviderInterface::class),
    );
  }


  /**
   * Attempts to find a parent route for a given route, based on the path.
   *
   * @param \Symfony\Component\Routing\Route $route
   *   The route for which to find a parent.
   *
   * @return string|null
   *   A parent route name, or NULL if none found.
   */
  protected function routeGetParentName(Route $route): ?string {

    $path = $route->getPath();
    $parent_path = \dirname($path);
    if ($parent_path === '/' || $parent_path === '') {
      return NULL;
    }

    $candidate_routes = $this->provider->getRoutesByPattern($parent_path);
    foreach ($candidate_routes as $candidate_name => $candidate_route) {
      if ($candidate_route->getPath() === $parent_path) {
        return $candidate_name;
      }
    }

    return NULL;
  }

}
