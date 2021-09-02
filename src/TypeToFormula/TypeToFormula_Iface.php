<?php
declare(strict_types=1);

namespace Donquixote\ObCK\TypeToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Iface\Formula_Iface;

/**
 * This is a version of TypeToFormula* where $type is assumed to be an interface
 * name.
 */
class TypeToFormula_Iface implements TypeToFormulaInterface {

  /**
   * {@inheritdoc}
   */
  public function typeGetFormula(string $type, bool $or_null): FormulaInterface {
    return new Formula_Iface($type);
  }
}
