<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\PluginListRecursive;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface;

/**
 * Formula allowing a choice based on a plugin list.
 */
interface Formula_PluginListRecursiveInterface extends FormulaInterface {

  /**
   * Gets the plugin list formula.
   *
   * @return \Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface
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
