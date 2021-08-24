<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaConfToAnything;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface FormulaConfToAnythingInterface {

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param mixed $conf
   * @param string $interface
   *
   * @return object
   *   An instance of $interface.
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public function formula(FormulaInterface $formula, $conf, string $interface): object;

}
