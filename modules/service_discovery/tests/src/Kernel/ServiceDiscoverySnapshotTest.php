<?php

declare(strict_types = 1);

namespace Drupal\Tests\service_discovery\Kernel;

use Ock\DrupalTesting\ModuleSnapshotTestBase;

class ServiceDiscoverySnapshotTest extends ModuleSnapshotTestBase {

  /**
   * {@inheritdoc}
   */
  public static function getTestedModuleNames(): array {
    return [
      'service_discovery',
      'service_discovery_test',
    ];
  }

}
