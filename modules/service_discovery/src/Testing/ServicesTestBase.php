<?php

declare(strict_types=1);

namespace Drupal\service_discovery\Testing;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\KernelTests\KernelTestBase;
use Ock\DrupalTesting\DrupalTesting;
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
    $module_installer = DrupalTesting::service(ModuleInstallerInterface::class);
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
    $module_installer = DrupalTesting::service(ModuleInstallerInterface::class);
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
   * Verifies tagged service ids.
   */
  public function testTaggedServicesAsRecorded(): void {
    $container = \Drupal::getContainer();
    $this->assertInstanceOf(ContainerBuilder::class, $container);
    $tag_names = $container->findTags();
    $report = [];
    foreach ($tag_names as $tag_name) {
      foreach ($container->findTaggedServiceIds($tag_name) as $service_id => $tags_info) {
        if ($tags_info === [[]]) {
          $tags_info = '[[]]';
        }
        $report[$tag_name][$service_id] = $tags_info;
      }
    }

    // Uninstall the module, but keep dependencies enabled.
    $module_installer = DrupalTesting::service(ModuleInstallerInterface::class);
    $module = $this->getTestedModuleName();
    $module_installer->uninstall([$module]);
    $container = \Drupal::getContainer();

    // Remove tagged services that still exist without this module.
    $tag_names = $container->findTags();
    foreach ($tag_names as $tag_name) {
      foreach ($container->findTaggedServiceIds($tag_name) as $service_id => $tags_info) {
        if ($tags_info === [[]]) {
          $tags_info = '[[]]';
        }
        $tags_info_while_module_installed = $report[$tag_name][$service_id] ?? NULL;
        // For now, assume that installing a module only _adds_ new tags and
        // tagged services, but does not remove or change them.
        // If this ever changes, the test needs to become more sophisticated.
        $this->assertSame($tags_info_while_module_installed, $tags_info, "Service '$service_id' tagged with '$tag_name'.");
        unset($report[$tag_name][$service_id]);
      }
    }

    // Remove tags that have not changed.
    $report = array_filter($report);

    // Sort the array and its children.
    ksort($report);
    array_walk($report, fn (&$arr) => ksort($arr));

    $this->assertAsRecorded($report, "Tagged services from '$module' module.", 4);
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
