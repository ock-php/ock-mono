<?php

declare(strict_types = 1);

namespace Drupal\Tests\controller_attributes\Unit;

use Drupal\controller_attributes\Hook\LinksFromRoutes;
use Drupal\Core\Menu\LocalActionManagerInterface;
use Drupal\Core\Menu\LocalTaskManagerInterface;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\Tests\controller_attributes\Traits\ExceptionTestTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

#[CoversClass(LinksFromRoutes::class)]
class LinksFromRouteTest extends TestCase {

  use ExceptionTestTrait;

  public function testMenuLinksDiscoveredAlter(): void {
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
    $hook_object = new LinksFromRoutes(
      $route_provider,
      $this->createMock(LocalActionManagerInterface::class),
      $this->createMock(LocalTaskManagerInterface::class),
    );
    $links = [
      'existing' => [],
    ];
    $hook_object->menuLinksDiscoveredAlter($links);
    // @todo This should be skipped instead.
    $this->assertSame([
      'existing' => [],
      'routelink:route_with_non_array_menu_link' => [
        'title' => 'Non-array menu link',
        'route_name' => 'route_with_non_array_menu_link',
        'parent' => null,
      ],
      'routelink:route_with_menu_link' => [
        'title' => 'Test menu link',
        'parent' => 'parent_route_name',
        'route_name' => 'route_with_menu_link',
     ],
      'routelink:auto_parent' => [
        'title' => 'Link with auto parent',
        'route_name' => 'auto_parent',
        'parent' => 'routelink:route_with_menu_link',
      ],
    ], $links);
  }

  public function testMenuLocalActionsAlter(): void {
    $route_provider = $this->createMock(RouteProviderInterface::class);
    // Only  test cases that are not covered indirectly in kernel tests.
    $route_provider->method('getAllRoutes')
      ->willReturn([
        'route_with_no_action_link' => (new Route('/path/to/a')),
        'route_with_non_array_action_link' => (new Route('/path/to/b'))
          ->setOption('_action_link', 'non_array_value')
          ->setDefault('_title', 'Non-array action link'),
        'route_with_action_link' => (new Route('/path/to/c'))
          ->setOption('_action_link', [
            'title' => 'Test action link',
            'appears_on' => ['appears_on_route'],
          ]),
        'parent' => $parent = (new Route('/parent')),
        'auto_appears_on' => (new Route('/parent/child'))
          ->setDefault('_title', 'Appears on')
          ->setOption('_action_link', []),
        'auto_appears_on_fail' => (new Route('/non-existing-parent/child'))
          ->setDefault('_title', 'Appears on fail')
          ->setOption('_action_link', []),
        'non_array_appears_on' => (new Route('/appears-on/non-array'))
          ->setDefault('_title', 'Non-array appears on')
          ->setOption('_action_link', [
            'appears_on' => 'xyz',
          ]),
      ]);
    $route_provider->method('getRoutesByPattern')
      ->willReturnCallback(fn (string $pattern): RouteCollection => match ($pattern) {
        '/parent' => $this->createRouteCollection([
          'parent' => $parent,
        ]),
        '/non-existing-parent' => $this->createRouteCollection([]),
        default => $this->fail($pattern),
      });
    $hook_object = new LinksFromRoutes(
      $route_provider,
      $this->createMock(LocalActionManagerInterface::class),
      $this->createMock(LocalTaskManagerInterface::class),
    );
    $local_actions = ['existing' => []];
    $hook_object->menuLocalActionsAlter($local_actions);
    $this->assertSame([
      'existing' => [],
      'routelink:route_with_action_link' => [
        'title' => 'Test action link',
        'appears_on' => ['appears_on_route'],
        'route_name' => 'route_with_action_link',
      ],
      'routelink:auto_appears_on' => [
        'title' => 'Appears on',
        'route_name' => 'auto_appears_on',
        'appears_on' => ['parent'],
      ],
    ], $local_actions);
  }

  protected function createRouteCollection(array $routes): RouteCollection {
    $collection = new RouteCollection();
    foreach ($routes as $name => $route) {
      $collection->add($name, $route);
    }
    return $collection;
  }

}
