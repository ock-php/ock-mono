<?php

declare(strict_types=1);

namespace Donquixote\Ock\FormulaConfToAnything;

use Donquixote\Ock\Core\Formula\FormulaInterface;

interface FormulaConfToAnythingInterface {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param mixed $conf
   * @param string $interface
   *
   * @return object
   *   An instance of $interface.
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public function formula(FormulaInterface $formula, $conf, string $interface): object;

}
