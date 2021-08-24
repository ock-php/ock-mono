<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\DefinitionToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

/**
 * Objects to create formula based on definitions.
 *
 * Definitions arrays are the format in which components register their plugins.
 */
interface DefinitionToFormulaInterface {

  /**
   * Gets or creates a formula object from a given definition array.
   *
   * @param array $definition
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\ObCK\Exception\FormulaCreationException
   */
  public function definitionGetFormula(array $definition): FormulaInterface;

}
