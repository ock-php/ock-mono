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
    $definitions = DrupalTesting::service(LocalTaskManagerInterface::class)
      ->getDefinitions();
    foreach ($definitions as $id => $definition) {
      if (!array_key_exists('route_name', $definition)) {
        $definitions[$id]['route_name'] = '(missing)';
      }
      elseif ($definition['route_name'] === $id) {
        unset($definitions[$id]['route_name']);
      }
    }
    return $definitions;
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
