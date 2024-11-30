<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Snapshotter;

use Drupal\Core\Routing\RouteCompiler;
use Drupal\Core\Routing\RouteProviderInterface;
use Ock\DrupalTesting\DrupalTesting;
use Ock\Testing\Snapshotter\SnapshotterInterface;
use Symfony\Component\Routing\Route;

class Snapshotter_Routes implements SnapshotterInterface {

  public function takeSnapshot(): array {
    $routes = DrupalTesting::service(RouteProviderInterface::class)
      ->getAllRoutes();
    $default_route = new Route(
      '/###',
      options: [
        'compiler_class' => RouteCompiler::class,
        'utf8' => true,
      ],
      methods: ['GET', 'POST'],
    );
    $default_export = $this->exportRoute($default_route);
    $report = [];
    foreach ($routes as $id => $route) {
      $path = $route->getPath();
      $route_export = $this->exportRoute($route);
      $route_export_reduced = $this->subtractDefaults($route_export, $default_export);
      $report[$path][$id] = $route_export_reduced;
    }
    ksort($report);
    return $report;
  }

  protected function subtractDefaults(array $route_export, array $default_export): array {
    foreach ($default_export as $key => $default_value) {
      $route_export[$key] ??= null;
      if ($route_export[$key] === $default_value) {
        unset($route_export[$key]);
      }
    }
    foreach ($default_export['options'] ?? [] as $option_key => $default_option_value) {
      $route_export['options'][$option_key] ??= null;
      if ($route_export['options'][$option_key] === $default_option_value) {
        unset($route_export['options'][$option_key]);
      }
    }
    return $route_export;
  }

  protected function exportRoute(Route $route): array {
    return [
      'defaults' => $route->getDefaults(),
      'methods' => $route->getMethods(),
      'requirements' => $route->getRequirements(),
      'condition' => $route->getCondition(),
      'options' => $route->getOptions(),
      'host' => $route->getHost(),
      'schemes' => $route->getSchemes(),
    ];
  }

}
