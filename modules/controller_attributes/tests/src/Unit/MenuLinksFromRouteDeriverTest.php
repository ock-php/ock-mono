<?php

declare(strict_types = 1);

namespace Drupal\Tests\controller_attributes\Unit;

use Drupal\controller_attributes\PluginDeriver\LinkPluginDeriverBase;
use Drupal\controller_attributes\PluginDeriver\PluginDeriver_MenuLinksFromRouteMeta;
use Drupal\controller_attributes\PluginDeriver\PluginDeriverBase;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\Tests\controller_attributes\Traits\ExceptionTestTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

#[CoversClass(PluginDeriver_MenuLinksFromRouteMeta::class)]
#[CoversClass(PluginDeriverBase::class)]
#[CoversClass(LinkPluginDeriverBase::class)]
class MenuLinksFromRouteDeriverTest extends TestCase {

  use ExceptionTestTrait;

  public function testGetDerivativeDefinitions(): void {
    $route_provider = $this->createMock(RouteProviderInterface::class);
    // Only  test cases that are not covered indirectly in kernel tests.
    $route_provider->method('getAllRoutes')
      ->willReturn([
        'route_with_no_menu_link' => (new Route('/path/to/a')),
        'route_with_non_array_menu_link' => (new Route('/path/to/b'))
          ->setOption('_menu_link', 'non_array_value')
          ->setDefault('_title', 'Non-array menu link'),
        'route_with_menu_link' => $parent = (new Route('/path/to/c'))
          ->setOption('_menu_link', [
            'title' => 'Test menu link',
            'parent' => 'parent_route_name',
          ]),
        'auto_parent' => (new Route('/path/to/c/child'))
          ->setOption('_menu_link', [
            'title' => 'Link with auto parent',
          ]),
      ]);
    $route_provider->method('getRoutesByPattern')
      ->willReturnCallback(fn (string $pattern): RouteCollection => match ($pattern) {
        '/path/to' => $this->createRouteCollection([]),
        '/path/to/c' => $this->createRouteCollection([
          'route_with_menu_link' => $parent,
        ]),
      });
    $deriver = new PluginDeriver_MenuLinksFromRouteMeta($route_provider);
    $definitions = $deriver->getDerivativeDefinitions([]);
    // @todo This should be skipped instead.
    $this->assertSame([
      'route_with_non_array_menu_link' => [
        'title' => 'Non-array menu link',
        'route_name' => 'route_with_non_array_menu_link',
        'parent' => null,
      ],
      'route_with_menu_link' => [
        'title' => 'Test menu link',
        'parent' => 'parent_route_name',
        'route_name' => 'route_with_menu_link',
     ],
      'auto_parent' => [
        'title' => 'Link with auto parent',
        'route_name' => 'auto_parent',
        'parent' => 'routelink:route_with_menu_link',
      ],
    ], $definitions);
  }

  protected function createRouteCollection(array $routes): RouteCollection {
    $collection = new RouteCollection();
    foreach ($routes as $name => $route) {
      $collection->add($name, $route);
    }
    return $collection;
  }

}
