<?php

declare(strict_types=1);

namespace Ock\DrupalTesting;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Extension\ModuleExtensionList;
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
 * Base class to test which services a specific module provides.
 *
 * This is used to detect regressions if a service has disappeared or changed
 * accidentally.
 */
abstract class ModuleServicesTestBase extends KernelTestBase {

  use CurrentModuleTestTrait;
  use RecordedTestTrait;

  /**
   * Tests service ids from this module.
   *
   * This protects against services being removed or altered unexpectedly.
   *
   * @return list<string>
   *   Service ids from this module.
   */
  public function testServiceIds(): array {
    $module = $this->getTestedModuleName();
    $info = DrupalTesting::service(ModuleExtensionList::class)->get($module);
    assert(property_exists($info, 'requires'));
    $other_modules = array_keys($info->requires);

    DrupalTesting::service(ModuleInstallerInterface::class)->install($other_modules);
    $service_ids_without = \Drupal::getContainer()->getServiceIds();

    DrupalTesting::service(ModuleInstallerInterface::class)->install([$module]);
    $service_ids_with = \Drupal::getContainer()->getServiceIds();

    $module_service_ids = array_values(array_diff(
      $service_ids_with,
      [
        ...$service_ids_without,
        // The 'old' route provider service is added by Drupal core when a
        // more than one module is installed.
        // Having it in the report only causes confusion.
        'router.route_provider.old',
      ],
    ));
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
    $module = $this->getTestedModuleName();
    DrupalTesting::service(ModuleInstallerInterface::class)->install([$module]);
    $container = \Drupal::getContainer();
    $this->assertInstanceOf(ContainerBuilder::class, $container);
    $current_ids = $container->getServiceIds();
    $this->assertSame(
      [],
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
    $module = $this->getTestedModuleName();
    $info = DrupalTesting::service(ModuleExtensionList::class)->get($module);
    /** @var array<string, mixed> $module_dependencies */
    $module_dependencies = $info->requires ?? $this->fail('The ->requires key is empty.');
    $other_modules = array_keys($module_dependencies);
    DrupalTesting::service(ModuleInstallerInterface::class)->install($other_modules);

    $container = \Drupal::getContainer();
    $this->assertInstanceOf(ContainerBuilder::class, $container);
    $tag_names = $container->findTags();
    $report_without = [];
    foreach ($tag_names as $tag_name) {
      foreach ($container->findTaggedServiceIds($tag_name) as $service_id => $tags_info) {
        if ($tags_info === [[]]) {
          $tags_info = '[[]]';
        }
        $report_without[$tag_name][$service_id] = $tags_info;
      }
    }

    DrupalTesting::service(ModuleInstallerInterface::class)->install([$module]);

    // Find tagged services that were different or missing without the module.
    $container = \Drupal::getContainer();
    $this->assertInstanceOf(ContainerBuilder::class, $container);
    $tag_names = $container->findTags();
    $report = [];
    foreach ($tag_names as $tag_name) {
      foreach ($container->findTaggedServiceIds($tag_name) as $service_id => $tags_info) {
        if ($tags_info === [[]]) {
          $tags_info = '[[]]';
        }
        $tags_info_without = $report_without[$tag_name][$service_id] ?? NULL;
        if ($tags_info_without !== NULL) {
          // For now, assume that installing a module only _adds_ new tags and
          // tagged services, but does not remove or change them.
          // If this ever changes, the test needs to become more sophisticated.
          $this->assertSame($tags_info_without, $tags_info, "Service '$service_id' tagged with '$tag_name'.");
          continue;
        }
        $report[$tag_name][$service_id] = $tags_info;
      }
    }

    // Sort the array and its children.
    ksort($report);
    array_walk($report, fn (&$arr) => ksort($arr));

    $this->assertAsRecorded($report, "Tagged services from '$module' module.");
  }

}
