<?php
declare(strict_types=1);

namespace Drupal\controller_attributes\PluginDeriver;

use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\Routing\RouteProviderInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

abstract class LinkPluginDeriverBase extends PluginDeriverBase implements ContainerDeriverInterface {

  public function __construct(
    protected RouteProviderInterface $provider,
    private readonly RouterInterface $router,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id): static {
    return new static(
      $container->get(RouteProviderInterface::class),
      $container->get('router.no_access_checks'),
    );
  }


  /**
   * @param \Symfony\Component\Routing\Route $route
   *
   * @return string|null
   */
  protected function routeGetParentName(Route $route): ?string {

    $path = $route->getPath();
    $parent_path = \dirname($path);

    try {
      $parent_route = $this->router->match($parent_path);
    }
    catch (\Exception $e) {
      // @todo Don't just silently ignore this.
      unset($e);
      return NULL;
    }

    if (empty($parent_route['_route'])) {
      return NULL;
    }

    return $parent_route['_route'];
  }

}
