<?php

declare(strict_types=1);

namespace Drupal\service_discovery\Testing;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\KernelTests\KernelTestBase;
use Ock\Testing\RecordedTestTrait;
use Psr\Container\NotFoundExceptionInterface;

if (!class_exists(KernelTestBase::class)
  || !trait_exists(RecordedTestTrait::class)
) {
  // Do not declare the below class if the dependencies are not present.
  return;
}

/**
 * Base class for services tests.
 */
abstract class ServicesTestBase extends KernelTestBase {

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
   * Tests service ids from this module.
   *
   * This protects against services being removed or altered unexpectedly.
   *
   * @return list<string>
   *   Service ids from this module.
   */
  public function testServiceIds(): array {
    /** @var ModuleInstallerInterface $module_installer */
    $module_installer = \Drupal::service(ModuleInstallerInterface::class);
    $module = $this->getTestedModuleName();
    $all_ids = \Drupal::getContainer()->getServiceIds();
    // Uninstall the modules, but keep dependencies enabled.
    $module_installer->uninstall([$module]);
    $remaining_ids = \Drupal::getContainer()->getServiceIds();
    $module_service_ids = array_values(array_diff($all_ids, $remaining_ids));
    sort($module_service_ids);
    $this->assertAsRecorded($module_service_ids, "Service ids from '$module' module.");
    return $module_service_ids;
  }

  /**
   * Tests that all the service ids are working, if the service is public.
   *
   * @param list<string> $service_ids
   *   Service ids returned from the other test method.
   *
   * @depends testServiceIds
   */
  public function testPublicServicesExist(array $service_ids): void {
    $container = \Drupal::getContainer();
    $this->assertInstanceOf(ContainerBuilder::class, $container);
    $current_ids = $container->getServiceIds();
    $this->assertEmpty(
      array_diff($service_ids, $current_ids),
      'Some previous service ids are missing.',
    );
    $types = [];
    foreach ($service_ids as $id) {
      try {
        $service = $this->container->get($id);
      }
      catch (NotFoundExceptionInterface) {
        // Some services and aliases are not publicly available.
        // This is fine, as long as it does not change unexpectedly.
        continue;
      }
      catch (\Throwable $e) {
        // Some services are broken.
        // This needs to lead to a test failure.
        // Still re-throw to report the service id.
        /** @noinspection PhpUnhandledExceptionInspection */
        throw new \Exception("Failed to get service '$id':\n" . $e->getMessage(), previous: $e);
      }
      $types[$id] = get_debug_type($service);
      if ($types[$id] === $id) {
        $types[$id] = '=';
      }
    }
    $this->assertAsRecorded($types, 'Service types.');
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

}
