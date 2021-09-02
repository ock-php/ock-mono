<?php
declare(strict_types=1);

namespace Donquixote\ObCK\TypeToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface TypeToFormulaInterface {

  /**
   * Gets a formula for a type name.
   *
   * A generator based on this formula should produce instances of the given
   * type.
   *
   * @param string $type
   *   Type, usually an interface name.
   * @param bool $or_null
   *   TRUE if this is optional.
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   *   Formula for the type.
   */
  public function typeGetFormula(string $type, bool $or_null): FormulaInterface;

}
