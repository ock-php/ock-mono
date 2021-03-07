<?php

namespace Donquixote\OCUI\Formula\Drilldown\Option;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Select\Option\SelectOptionInterface;

/**
 * Object representation of a plugin definition.
 *
 * Experimental, not used yet.
 *
 * To make this work, the object should be serializable for caching purposes.
 */
interface DrilldownOptionInterface extends SelectOptionInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function getFormula(): FormulaInterface;

}
