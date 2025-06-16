<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Snapshotter;

use Drupal\Core\Menu\LocalActionDefault;
use Drupal\Core\Menu\LocalActionManagerInterface;
use Ock\DrupalTesting\DrupalTesting;

class Snapshotter_ActionLinks extends SnapshotterBase {

  /**
   * {@inheritdoc}
   */
  protected function getItems(): array {
    $definitions = DrupalTesting::service(LocalActionManagerInterface::class)
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
      'id' => null,
      'route_parameters' => [],
      'weight' => null,
      'options' => [],
      'class' => LocalActionDefault::class,
    ];
  }

}
