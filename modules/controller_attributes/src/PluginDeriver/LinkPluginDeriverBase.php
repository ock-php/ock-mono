<?php
declare(strict_types=1);

namespace Drupal\controller_attributes\PluginDeriver;

use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\service_discovery\CallbackResolverInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

abstract class LinkPluginDeriverBase extends PluginDeriverBase implements ContainerDeriverInterface {

  /**
   * Static factory.
   *
   * @param \Psr\Container\ContainerInterface $container
   * @param string $base_plugin_id
   *
   * @return static
   *
   * @throws \Psr\Container\ContainerExceptionInterface
   *   Some arguments are left unresolved.
   */
  public static function create(ContainerInterface $container, $base_plugin_id): static {
    $resolver = $container->get(CallbackResolverInterface::class);
    assert($resolver instanceof CallbackResolverInterface);
    return $resolver
      ->withTypeArgs(['string' => $base_plugin_id])
      ->construct(static::class);
  }

  /**
   * @param \Drupal\Core\Routing\RouteProviderInterface $provider
   * @param \Symfony\Component\Routing\RouterInterface $router
   */
  public function __construct(
    #[Autowire(service: 'router.route_provider')]
    protected RouteProviderInterface $provider,
    #[Autowire(service: 'router.no_access_checks')]
    private readonly RouterInterface $router,
  ) {}

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
