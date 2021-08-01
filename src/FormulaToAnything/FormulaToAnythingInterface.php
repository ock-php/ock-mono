<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface FormulaToAnythingInterface {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param string $interface
   *
   * @return object
   *   An instance of $interface, or
   *   NULL, if no adapter can be found.
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   *   Object cannot be created for the given formula.
   */
  public function formula(FormulaInterface $formula, string $interface): object;

}
