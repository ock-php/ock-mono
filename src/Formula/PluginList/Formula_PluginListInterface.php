<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\PluginList;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

/**
 * Formula allowing a choice based on a plugin list.
 */
interface Formula_PluginListInterface extends FormulaInterface {

  /**
   * Gets an associative list of plugins.
   *
   * @return \Donquixote\ObCK\Plugin\Plugin[]
   *   List of plugins by machine name.
   */
  public function getPlugins(): array;

  /**
   * Checks if the formula is optional.
   *
   * If so, an empty-ish configuration will produce NULL.
   *
   * @return bool
   *   TRUE if optional.
   */
  public function allowsNull(): bool;

}
