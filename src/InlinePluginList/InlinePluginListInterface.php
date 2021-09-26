<?php

declare(strict_types=1);

namespace Donquixote\Ock\InlinePluginList;

use Donquixote\Ock\Plugin\Plugin;

interface InlinePluginListInterface {

  /**
   * Gets an associative list of plugins.
   *
   * @return string[]
   *   List of plugins machine names.
   *
   * @throws \Donquixote\Ock\Exception\FormulaException
   */
  public function getIds(): array;

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

}
