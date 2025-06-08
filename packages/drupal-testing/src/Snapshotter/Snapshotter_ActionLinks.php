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
    return DrupalTesting::service(LocalActionManagerInterface::class)
      ->getDefinitions();
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
