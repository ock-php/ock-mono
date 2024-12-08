<?php

declare(strict_types = 1);

namespace Drupal\Tests\controller_attributes\Kernel;

use Ock\DrupalTesting\ModuleSnapshotTestBase;

class ControllerAttributesSnapshotTest extends ModuleSnapshotTestBase {

  /**
   * {@inheritdoc}
   */
  protected static function getTestedModuleNames(): array {
    return [
      'controller_attributes',
      'controller_attributes_test',
    ];
  }

}
