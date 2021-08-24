<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaBase;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

/**
 * Base interface for all formula types where the configuration form and summary
 * is the same as for the decorated formula.
 */
interface Formula_ValueToValueBaseInterface extends FormulaInterface {

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

}
