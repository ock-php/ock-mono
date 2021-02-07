<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToFormula;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContext;

/**
 * This is a version of TypeToFormula* where $type is assumed to be an interface
 * name.
 */
class TypeToFormula_Iface implements TypeToFormulaInterface {

  /**
   * {@inheritdoc}
   */
  public function typeGetFormula(string $type, CfContextInterface $context = NULL): FormulaInterface {
    return new Formula_IfaceWithContext($type, $context);
  }
}
