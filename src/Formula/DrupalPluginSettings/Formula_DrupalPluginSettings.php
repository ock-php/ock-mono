<?php

declare(strict_types=1);

namespace Drupal\ock\Formula\DrupalPluginSettings;

class Formula_DrupalPluginSettings implements Formula_DrupalPluginSettingsInterface {

  /**
   * Constructor.
   *
   * @param object $plugin
   */
  public function __construct(private readonly object $plugin) {
  }

  /**
   * {@inheritdoc}
   */
  public function getPlugin(): object {
    return $this->plugin;
  }

}
