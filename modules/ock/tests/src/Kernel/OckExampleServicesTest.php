<?php

declare(strict_types=1);

namespace Drupal\Tests\ock\Kernel;

use Ock\DrupalTesting\ModuleServicesTestBase;

/**
 * Test for services from 'ock_example' module.
 */
class OckExampleServicesTest extends ModuleServicesTestBase {

  /**
   * {@inheritdoc}
   */
  protected function getTestedModuleName(): string {
    return 'ock_example';
  }

}
