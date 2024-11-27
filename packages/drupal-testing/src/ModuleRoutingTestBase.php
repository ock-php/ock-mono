<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting;

use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\KernelTests\KernelTestBase;
use Ock\Testing\Exporter\Exporter_ToYamlArray;
use Ock\Testing\Exporter\ExporterInterface;
use Ock\Testing\RecordedTestTrait;
use Symfony\Component\Routing\Route;

abstract class ModuleRoutingTestBase extends KernelTestBase {

  use CurrentModuleTestTrait;
  use RecordedTestTrait;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    # $this->installEntitySchema('user');
    # $this->installSchema('user', ['users_data']);
  }

  /**
   * Tests route definitions from this module.
   */
  public function testRoutes(): void {
    $module = $this->getTestedModuleName();
    $info = DrupalTesting::service(ModuleExtensionList::class)->get($module);
    assert(property_exists($info, 'requires'));
    $other_modules = array_keys($info->requires);

    DrupalTesting::service(ModuleInstallerInterface::class)->install($other_modules);
    /** @var \ArrayIterator<\Symfony\Component\Routing\Route> $routes_without */
    $routes_without = DrupalTesting::service(RouteProviderInterface::class)->getAllRoutes();

    DrupalTesting::service(ModuleInstallerInterface::class)->install([$module]);
    /** @var \ArrayIterator<\Symfony\Component\Routing\Route> $routes_with */
    $routes_with = DrupalTesting::service(RouteProviderInterface::class)->getAllRoutes();

    $module_routes = array_diff_key(
      $routes_with->getArrayCopy(),
      $routes_without->getArrayCopy(),
    );
    ksort($module_routes);
    $this->assertObjectsAsRecorded(
      $module_routes,
      "Routes from '$module' module.",
      defaultClass: Route::class,
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function createExporter(): ExporterInterface {
    return (new Exporter_ToYamlArray())
      ->withObjectGetters(Route::class)
      ->withDefaultObject(new Route('#'));
  }

}
