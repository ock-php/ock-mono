<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Snapshotter;

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

}
