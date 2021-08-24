<?php

namespace Donquixote\ObCK\Formula\Drilldown\Option;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Select\Option\SelectOptionInterface;

/**
 * Object representation of a plugin definition.
 *
 * Experimental, not used yet.
 *
 * To make this work, the object should be serializable for caching purposes.
 */
interface DrilldownOptionInterface extends SelectOptionInterface {

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function getFormula(): FormulaInterface;

}
