<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToFormula;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

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
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\OCUI\Exception\FormulaCreationException
   */
  public function definitionGetFormula(array $definition): FormulaInterface;

}
