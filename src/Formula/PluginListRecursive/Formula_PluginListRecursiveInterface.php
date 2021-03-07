<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Formula\PluginListRecursive;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\PluginList\Formula_PluginListInterface;

/**
 * Formula allowing a choice based on a plugin list.
 */
interface Formula_PluginListRecursiveInterface extends FormulaInterface {

  /**
   * Gets the plugin list formula.
   *
   * @return \Donquixote\OCUI\Formula\PluginList\Formula_PluginListInterface
   *   The actual plugin list formula.
   */
  public function getPluginListFormula(): Formula_PluginListInterface;

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
