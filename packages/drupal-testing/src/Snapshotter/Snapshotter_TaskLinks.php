<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Snapshotter;

use Drupal\Core\Menu\LocalActionManagerInterface;
use Drupal\Core\Menu\LocalTaskDefault;
use Ock\DrupalTesting\DrupalTesting;

class Snapshotter_TaskLinks extends SnapshotterBase {

  /**
   * {@inheritdoc}
   */
  protected function getItems(): array {
    return DrupalTesting::service(LocalActionManagerInterface::class)
      ->getDefinitions();
  }

  /**
   * {@inheritdoc}
   */
  protected function getDefaultItem(): array {
    return [
      'route_parameters' => [],
      'parent_id' => NULL,
      'weight' => NULL,
      'options' => [],
      'base_route' => null,
      'class' => LocalTaskDefault::class,
      'id' => '',
    ];
  }

}
