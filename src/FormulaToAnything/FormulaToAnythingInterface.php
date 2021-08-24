<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaToAnything;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface FormulaToAnythingInterface {

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param string $interface
   *
   * @return object
   *   An instance of $interface.
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   *   Object cannot be created for the given formula.
   */
  public function formula(FormulaInterface $formula, string $interface): object;

}
