<?php

declare(strict_types = 1);

namespace Drupal\Tests\controller_attributes\Kernel;

use Drupal\controller_attributes\AttributesRouteProviderBase;
use Drupal\controller_attributes_test\Controller\HelloController;
use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\Core\Routing\RouteCompiler;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\KernelTests\KernelTestBase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Routing\Route;

#[CoversClass(AttributesRouteProviderBase::class)]
class ControllerAttributesRouteProviderTest extends KernelTestBase {

  public function testRoutes(): void {
    $routes_before = $this->collectRoutes();

    $this->getService(ModuleInstallerInterface::class)->install(['controller_attributes']);
    $this->assertEquals($routes_before, $this->collectRoutes());

    $this->getService(ModuleInstallerInterface::class)->install(['controller_attributes_test']);
    $expected_routes = [
      ...$routes_before,
      'controller_attributes_test.hello.hello' => $this->createExpectedRoute('/controller-attributes-test/hello')
        ->setDefault('_controller', HelloController::class . '::hello'),
    ];
    $this->assertEquals(array_keys($expected_routes), array_keys($this->collectRoutes()));
    foreach ($expected_routes as $name => $expected_route) {
      $this->assertEquals($expected_route, $this->collectRoutes()[$name], $name);
    }
  }

  /**
   * Creates a route object to compare with.
   */
  protected function createExpectedRoute(string $path): Route {
    $route = new Route(
      $path,
      options: [
        'compiler_class' => RouteCompiler::class,
        'utf8' => TRUE,
      ],
      methods: ['GET', 'POST'],
    );
    return $route;
  }

  /**
   * Gets routes for currently installed modules.
   *
   * @return array<string, \Symfony\Component\Routing\Route>
   *   Routes without the 'compiled' property.
   */
  protected function collectRoutes(): array {
    return array_map(
      function (Route $route) {
        $route = clone $route;
        (new \ReflectionProperty($route, 'compiled'))->setValue($route, NULL);
        return $route;
      },
      $this->getService(RouteProviderInterface::class)
        ->getAllRoutes()->getArrayCopy(),
    );
  }

  /**
   * @template T of object
   *
   * @param class-string<T> $name
   *
   * @return T&object
   */
  protected function getService(string $name): object {
    $service = \Drupal::service($name);
    $this->assertInstanceOf($name, $service);
    return $service;
  }

}
