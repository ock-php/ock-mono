<?php

declare(strict_types=1);

namespace Drupal\cu\Formula\DrupalPluginSettings;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

/**
 * Formula type for Drupal plugin settings.
 */
interface Formula_DrupalPluginSettingsInterface extends FormulaInterface {

  /**
   * Gets the Drupal plugin object.
   *
   * @return object
   */
  public function getPlugin(): object;

}
