<?php

declare(strict_types=1);

namespace Drupal\Tests\renderkit\Kernel;

use Ock\DrupalTesting\ServicesTestBase;

class RenderkitExampleServicesTest extends ServicesTestBase {

  /**
   * {@inheritdoc}
   */
  public function getTestedModuleName(): string {
    return 'renderkit_example';
  }

}
