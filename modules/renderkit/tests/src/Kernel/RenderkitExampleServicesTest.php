<?php

declare(strict_types=1);

namespace Drupal\Tests\renderkit\Kernel;

use Drupal\service_discovery\Testing\ServicesTestBase;

class RenderkitExampleServicesTest extends ServicesTestBase {

  /**
   * Additional modules to enable.
   */
  protected static $modules = [
    'ock',
    'renderkit',
  ];

  /**
   * {@inheritdoc}
   */
  public function getTestedModuleName(): string {
    return 'renderkit_example';
  }

}
