<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToFormula;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Definition\Formula_Definition;

class DefinitionToFormula_Simple implements DefinitionToFormulaInterface {

  /**
   * {@inheritdoc}
   */
  public function definitionGetFormula(array $definition): FormulaInterface {
    return new Formula_Definition($definition);
  }
}
