<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\PluginList;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Plugin\Plugin;

/**
 * Formula allowing a choice based on a plugin list.
 */
interface Formula_PluginListInterface extends FormulaInterface {

  /**
   * Gets an associative list of plugins.
   *
   * @return \Donquixote\Ock\Plugin\Plugin[]
   *   List of plugins by machine name.
   */
  public function getPlugins(): array;

  /**
   * Gets a specific plugin.
   *
   * @param string $id
   *   Id of the plugin.
   *
   * @return \Donquixote\Ock\Plugin\Plugin|null
   *   The plugin, or NULL if not found.
   */
  public function idGetPlugin(string $id): ?Plugin;

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
