<?php

declare(strict_types=1);

namespace Donquixote\ObCK\InlinePluginList;

use Donquixote\ObCK\Plugin\Plugin;

interface InlinePluginListInterface {

  /**
   * Gets an associative list of plugins.
   *
   * @return string[]
   *   List of plugins machine names.
   */
  public function getIds(): array;

  /**
   * Gets a specific plugin.
   *
   * @param string $id
   *   Id of the plugin.
   *
   * @return \Donquixote\ObCK\Plugin\Plugin|null
   *   The plugin, or NULL if not found.
   */
  public function idGetPlugin(string $id): ?Plugin;

}
