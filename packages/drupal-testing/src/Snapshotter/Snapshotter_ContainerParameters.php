<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Snapshotter;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use PHPUnit\Framework\Assert;

class Snapshotter_ContainerParameters extends AdvancedSnapshotterBase {

  /**
   * {@inheritdoc}
   */
  protected function getItems(): array {
    $container = \Drupal::getContainer();
    Assert::assertInstanceOf(ContainerBuilder::class, $container);
    $parameters = $container->getParameterBag()->all();
    ksort($parameters);
    // Make the output independent of module installation path.
    foreach ($parameters['container.modules'] as &$info) {
      if (isset($info['pathname'])) {
        $info['pathname'] = preg_replace('#^modules/(contrib|custom)/#', 'modules/*/', $info['pathname']);
      }
    }
    foreach ($parameters['container.namespaces'] as &$dir) {
      $dir = preg_replace('#^modules/(contrib|custom)/#', 'modules/*/', $dir);
    }
    // Remove parameters which only occur in specific Drupal versions.
    // Hook implementations are better checked with a dedicated snapshotter.
    unset($parameters['hook_implementations_map']);
    return $parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function compare(array $before, array $after): array {
    // Avoid uninteresting diff from new modules.
    $new_modules = array_diff_key($after['container.modules'], $before['container.modules']);
    foreach ($new_modules as $module => $info) {
      $before['container.modules'][$module] = $info;
      $src_path = preg_replace("#/$module\.info\.yml$#", '/src', $info['pathname']);
      $before['container.namespaces']["Drupal\\$module"] = $src_path;
    }
    return parent::compare($before, $after);
  }

}
