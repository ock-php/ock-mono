<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Snapshotter;

use Drupal\Core\Routing\RouteProviderInterface;
use Ock\DrupalTesting\DrupalTesting;
use Ock\Testing\Diff\DifferInterface;
use Ock\Testing\Snapshotter\SnapshotterInterface;

class Snapshotter_RoutePaths implements SnapshotterInterface, DifferInterface {

  /**
   * {@inheritdoc}
   */
  public function takeSnapshot(): array {
    $routes = DrupalTesting::service(RouteProviderInterface::class)
      ->getAllRoutes()->getArrayCopy();
    $report = [];
    foreach ($routes as $route) {
      $report[] = $route->getPath();
    }
    sort($report);
    return $report;
  }

  /**
   * {@inheritdoc}
   */
  public function compare(array $before, array $after): array {
    return array_filter([
      '--' => array_values(array_diff($before, $after)),
      '++' => array_values(array_diff($after, $before)),
    ]);
  }

}
