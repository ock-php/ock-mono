<?php

declare(strict_types = 1);

namespace Ock\DrupalTesting\Snapshotter;

use Drupal\Core\Menu\Form\MenuLinkDefaultForm;
use Drupal\Core\Menu\MenuLinkDefault;
use Drupal\Core\Menu\MenuLinkManagerInterface;
use Ock\DrupalTesting\DrupalTesting;

class Snapshotter_MenuLinks extends SnapshotterBase {

  /**
   * {@inheritdoc}
   */
  protected function getItems(): array {
    $definitions = DrupalTesting::service(MenuLinkManagerInterface::class)
      ->getDefinitions();
    foreach ($definitions as $id => $definition) {
      if (!array_key_exists('id', $definition)) {
        $definitions[$id]['id'] = '(missing)';
      }
      elseif ($definition['id'] === $id) {
        unset($definitions[$id]['id']);
      }
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
      'route_parameters' => [],
      'url' => '',
      'description' => '',
      'parent' => '',
      'weight' => 0,
      'options' => [],
      'expanded' => 0,
      'enabled' => 1,
      'provider' => '',
      'metadata' => [],
      'class' => MenuLinkDefault::class,
      'form_class' => MenuLinkDefaultForm::class,
    ];
  }

}
