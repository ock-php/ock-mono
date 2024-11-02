<?php

declare(strict_types=1);

namespace Drupal\Tests\renderkit\Kernel;

use Ock\DrupalTesting\ModuleServicesTestBase;

class RenderkitExampleServicesTest extends ModuleServicesTestBase {

  /**
   * {@inheritdoc}
   */
  public function getTestedModuleName(): string {
    return 'renderkit_example';
  }

}
