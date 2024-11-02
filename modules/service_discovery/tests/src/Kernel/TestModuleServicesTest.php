<?php

declare(strict_types=1);

namespace Drupal\Tests\service_discovery\Kernel;

use Ock\DrupalTesting\ServicesTestBase;

class TestModuleServicesTest extends ServicesTestBase {

  /**
   * {@inheritdoc}
   */
  protected function getTestedModuleName(): string {
    return 'service_discovery_test';
  }

}
