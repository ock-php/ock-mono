<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Snapshotter;

use Drupal\Core\Menu\LocalTaskDefault;
use Drupal\Core\Menu\LocalTaskManagerInterface;
use Ock\DrupalTesting\DrupalTesting;

class Snapshotter_TaskLinks extends SnapshotterBase {

  /**
   * {@inheritdoc}
   */
  protected function getItems(): array {
    return DrupalTesting::service(LocalTaskManagerInterface::class)
      ->getDefinitions();
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultItem(): array {
    return [
      'id' => '',
      'weight' => null,
      'route_parameters' => [],
      'base_route' => null,
      'parent_id' => null,
      'options' => [],
      'class' => LocalTaskDefault::class,
    ];
  }

}
