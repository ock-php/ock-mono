<?php

declare(strict_types = 1);

namespace Drupal\Tests\ock\Kernel;

use Ock\DrupalTesting\ModuleSnapshotTestBase;

class OckSnapshotTest extends ModuleSnapshotTestBase {

  public static function getTestedModuleNames(): array {
    return [
      'ock',
      'ock_example',
    ];
  }

}
