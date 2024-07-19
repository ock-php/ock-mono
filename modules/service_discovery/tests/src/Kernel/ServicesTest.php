<?php

declare(strict_types=1);

namespace Drupal\Tests\service_discovery\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Ock\Testing\RecordedTestTrait;

class ServicesTest extends KernelTestBase {

  use RecordedTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'service_discovery_test',
  ];

  public function testServices(): void {
    $container = \Drupal::getContainer();
    $all_ids = $container->getServiceIds();
    $ids = \preg_grep('#service_discovery_test#', $all_ids);
    $services = \array_map(
      $container->get(...),
      \array_combine($ids, $ids),
    );
    $this->assertObjectsAsRecorded(
      $services,
      arrayKeyIsDefaultClass: true,
    );
  }

}
