<?php

declare(strict_types=1);

namespace Drupal\Tests\renderkit\Kernel;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\KernelTests\KernelTestBase;
use Ock\Testing\RecordedTestTrait;

class RenderkitServicesTest extends KernelTestBase {

  use RecordedTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'user',
    'service_discovery',
    'ock',
    'renderkit',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('user');
    $this->installSchema('user', ['users_data']);
  }

  /**
   * Tests service ids from this module.
   *
   * This protects against services being removed accidentally.
   *
   * @return list<string>
   *   Service ids from this module.
   */
  public function testServiceIds(): array {
    $container = \Drupal::getContainer();
    $all_ids = $container->getServiceIds();
    /** @var ModuleInstallerInterface $module_installer */
    $module_installer = \Drupal::service(ModuleInstallerInterface::class);
    $module_installer->uninstall(['renderkit']);
    $remaining_ids = \Drupal::getContainer()->getServiceIds();
    $module_service_ids = array_values(array_diff($all_ids, $remaining_ids));
    sort($module_service_ids);
    $this->assertAsRecorded($module_service_ids, 'Service ids from renderkit module.');
    return $module_service_ids;
  }

  /**
   * @param list<string> $service_ids
   *
   * @depends testServiceIds
   */
  public function testServicesExist(array $service_ids): void {
    $container = \Drupal::getContainer();
    $this->assertInstanceOf(ContainerBuilder::class, $container);
    $types = [];
    foreach ($service_ids as $id) {
      $definition = $container->getDefinition($id);
      if (!$definition->isPublic()) {
        continue;
      }
      $service = \Drupal::service($id);
      $types[$id] = get_debug_type($service);
      if ($types[$id] === $id) {
        $types[$id] = '=';
      }
    }
    $this->assertAsRecorded($types, 'Service types.');
  }

}
