<?php

declare(strict_types = 1);

namespace Drupal\Tests\controller_attributes\Unit;

use Drupal\controller_attributes\PluginDeriver\LinkPluginDeriverBase;
use Drupal\controller_attributes\PluginDeriver\PluginDeriver_ActionLinksFromRouteMeta;
use Drupal\controller_attributes\PluginDeriver\PluginDeriverBase;
use Drupal\system\Tests\Routing\MockRouteProvider;
use Drupal\Tests\controller_attributes\Traits\ExceptionTestTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

#[CoversClass(PluginDeriver_ActionLinksFromRouteMeta::class)]
#[CoversClass(PluginDeriverBase::class)]
#[CoversClass(LinkPluginDeriverBase::class)]
class ActionLinksFromRouteDeriverTest extends TestCase {

  use ExceptionTestTrait;

  public function testGetDerivativeDefinitions(): void {
    $routes = [
      'route_with_no_action_link' => (new Route('/path/to/a')),
      'route_with_non_array_action_link' => (new Route('/path/to/b'))
        ->setOption('_action_link', 'non_array_value')
        ->setDefault('_title', 'Non-array action link'),
      'route_with_action_link' => (new Route('/path/to/c'))
        ->setOption('_action_link', [
          'title' => 'Test action link',
          'appears_on' => ['appears_on_route'],
        ]),
    ];
    $collection = new RouteCollection();
    foreach ($routes as $route_name => $route) {
      $collection->add($route_name, $route);
    }
    $route_provider = new MockRouteProvider($collection);
    $deriver = new PluginDeriver_ActionLinksFromRouteMeta($route_provider);
    $this->assertSame([
      'route_with_action_link' => [
        'title' => 'Test action link',
        'appears_on' => ['appears_on_route'],
        'route_name' => 'route_with_action_link',
      ],
    ], $deriver->getDerivativeDefinitions([]));
  }

}
