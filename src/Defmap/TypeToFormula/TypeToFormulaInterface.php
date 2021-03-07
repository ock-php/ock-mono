<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToFormula;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface TypeToFormulaInterface {

  /**
   * Gets a formula for a type name.
   *
   * A generator based on this formula should produce instances of the given
   * type.
   *
   * @param string $type
   *   Type, usually an interface name.
   * @param bool $orNull
   *   TRUE if this is optional.
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   *   Formula for the type.
   */
  public function typeGetFormula(string $type, bool $orNull): FormulaInterface;

}
