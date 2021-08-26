<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Plugin\Registry;

use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;

interface PluginRegistryInterface {

  /**
   *
   * @return \Donquixote\ObCK\Plugin\Plugin[][]
   *   Format: $[$type][$id] = $plugin
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public function getPluginss(): array;

}
