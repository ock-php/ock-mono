<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaBase;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

/**
 * Base interface for all schema types where the configuration form and summary
 * is the same as for the decorated schema.
 */
interface Formula_ValueToValueBaseInterface extends FormulaInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

}
