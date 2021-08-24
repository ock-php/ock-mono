<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\DefinitionToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Definition\Formula_Definition;

class DefinitionToFormula_Simple implements DefinitionToFormulaInterface {

  /**
   * {@inheritdoc}
   */
  public function definitionGetFormula(array $definition): FormulaInterface {
    return new Formula_Definition($definition);
  }
}
