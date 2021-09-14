<?php

declare(strict_types=1);

namespace Donquixote\Ock\FormulaBase;

use Donquixote\Ock\Core\Formula\FormulaInterface;

/**
 * Base interface for all formula types where the configuration form and summary
 * is the same as for the decorated formula.
 */
interface Formula_ValueToValueBaseInterface extends FormulaInterface {

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

}
