<?php

declare(strict_types=1);

namespace Drupal\ock\Formula\DrupalPluginSettings;

class Formula_DrupalPluginSettings implements Formula_DrupalPluginSettingsInterface {

  private object $plugin;

  /**
   * Constructor.
   *
   * @param object $plugin
   */
  public function __construct(object $plugin) {
    $this->plugin = $plugin;
  }

  /**
   * {@inheritdoc}
   */
  public function getPlugin(): object {
    return $this->plugin;
  }

}
