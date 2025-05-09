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
    return DrupalTesting::service(MenuLinkManagerInterface::class)
      ->getDefinitions();
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
