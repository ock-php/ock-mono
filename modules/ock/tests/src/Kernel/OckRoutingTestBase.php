<?php

declare(strict_types = 1);

namespace Drupal\Tests\ock\Kernel;

use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\KernelTests\KernelTestBase;
use Ock\Testing\Exporter\Exporter_ToYamlArray;
use Ock\Testing\Exporter\ExporterInterface;
use Ock\Testing\RecordedTestTrait;
use Symfony\Component\Routing\Route;

abstract class OckRoutingTestBase extends KernelTestBase {

  use RecordedTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'user',
    'service_discovery',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('user');
    $this->installSchema('user', ['users_data']);
    /** @var ModuleInstallerInterface $module_installer */
    $module_installer = \Drupal::service(ModuleInstallerInterface::class);
    $module = $this->getTestedModuleName();
    // Install the module and all dependencies.
    // Install one more module than we need, and uninstall it immediately after.
    // This prevents a bogus service id to appear in the test output.
    /** @noinspection PhpUnhandledExceptionInspection */
    $module_installer->install([$module, 'tour']);
    $module_installer->uninstall(['tour']);
  }

  /**
   * Tests route definitions from this module.
   */
  public function testRoutes(): void {
    /** @var \Drupal\Core\Routing\RouteProviderInterface $route_provider */
    $route_provider = \Drupal::service(RouteProviderInterface::class);
    /** @var ModuleInstallerInterface $module_installer */
    $module_installer = \Drupal::service(ModuleInstallerInterface::class);
    $module = $this->getTestedModuleName();
    /** @var \ArrayIterator<\Symfony\Component\Routing\Route> $all_routes_iterator */
    $all_routes_iterator = $route_provider->getAllRoutes();
    $module_installer->uninstall([$module]);
    /** @var \ArrayIterator<\Symfony\Component\Routing\Route> $remaining_routes_iterator */
    $remaining_routes_iterator = $route_provider->getAllRoutes();

    $module_routes = array_diff_key(
      $all_routes_iterator->getArrayCopy(),
      $remaining_routes_iterator->getArrayCopy(),
    );
    ksort($module_routes);
    $this->assertObjectsAsRecorded(
      $module_routes,
      "Routes from '$module' module.",
      7,
      Route::class,
    );
  }

  /**
   * Gets the module name the services of which to test.
   *
   * This base implementation uses a crude heuristic, and should be replaced
   * with a custom implementation if the heuristic does not work.
   *
   * @return string
   *   Module name.
   */
  protected function getTestedModuleName(): string {
    if (!preg_match('@^Drupal\\\\Tests\\\\(\w+)\\\\@', static::class, $matches)) {
      throw new \RuntimeException(sprintf('Class name %s does not imply a module name.', static::class));
    }
    return $matches[1];
  }

  /**
   * {@inheritdoc}
   */
  protected function createExporter(): ExporterInterface {
    return (new Exporter_ToYamlArray())
      ->withObjectGetters(Route::class)
      ->withReferenceObject(new Route('#'));
  }

}
