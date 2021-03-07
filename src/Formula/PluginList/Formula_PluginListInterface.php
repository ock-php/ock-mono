<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Formula\PluginList;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

/**
 * Formula allowing a choice based on a plugin list.
 */
interface Formula_PluginListInterface extends FormulaInterface {

  /**
   * Gets an associative list of plugins.
   *
   * @return \Donquixote\OCUI\Plugin\Plugin[]
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
