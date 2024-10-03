<?php

declare(strict_types=1);

namespace Drupal\Tests\service_discovery\Kernel;

use Drupal\service_discovery\Testing\ServicesTestBase;
use Ock\Testing\RecordedTestTrait;

class ServicesTest extends ServicesTestBase {

  use RecordedTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'service_discovery_test',
  ];

}
