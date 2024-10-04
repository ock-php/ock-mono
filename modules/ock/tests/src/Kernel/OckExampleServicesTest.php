<?php

declare(strict_types=1);

namespace Drupal\Tests\ock\Kernel;

use Drupal\service_discovery\Testing\ServicesTestBase;

/**
 * Test for services from 'ock_example' module.
 */
class OckExampleServicesTest extends ServicesTestBase {

  /**
   * Other modules to enable as dependencies.
   */
  protected static $modules = ['ock'];

  /**
   * {@inheritdoc}
   */
  protected function getTestedModuleName(): string {
    return 'ock_example';
  }

}
