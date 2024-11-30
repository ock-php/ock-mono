<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting;

use Drupal\KernelTests\KernelTestBase;
use Ock\DrupalTesting\Snapshotter\Snapshotter_ActionLinks;
use Ock\DrupalTesting\Snapshotter\Snapshotter_ContainerAliases;
use Ock\DrupalTesting\Snapshotter\Snapshotter_ContainerDefinitions;
use Ock\DrupalTesting\Snapshotter\Snapshotter_ContainerParameters;
use Ock\DrupalTesting\Snapshotter\Snapshotter_MenuLinks;
use Ock\DrupalTesting\Snapshotter\Snapshotter_RoutePaths;
use Ock\DrupalTesting\Snapshotter\Snapshotter_Routes;
use Ock\DrupalTesting\Snapshotter\Snapshotter_TaskLinks;

abstract class ModuleSnapshotTestBase extends KernelTestBase {

  use ModuleSnapshotTestTrait;

  /**
   * Gets the snapshot plugins to use for this test.
   *
   * @return array<string, \Ock\Testing\Snapshotter\SnapshotterInterface>
   */
  protected function getSnapshotters(): array {
    return [
      'links.action' => new Snapshotter_ActionLinks(),
      'links.menu' => new Snapshotter_MenuLinks(),
      'links.task' => new Snapshotter_TaskLinks(),
      'container.definitions' => new Snapshotter_ContainerDefinitions(),
      'container.aliases' => new Snapshotter_ContainerAliases(),
      'container.parameters' => new Snapshotter_ContainerParameters(),
      'route_paths' => new Snapshotter_RoutePaths(),
      'routes' => new Snapshotter_Routes(),
    ];
  }

}
