<?php

declare(strict_types=1);

namespace Donquixote\ObCK\InlineDrilldown;

use Donquixote\ObCK\Formula\Id\Formula_IdInterface;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;

interface InlineDrilldownInterface extends IdToFormulaInterface {

  /**
   * @return \Donquixote\ObCK\Formula\Id\Formula_IdInterface
   */
  public function getIdFormula(): Formula_IdInterface;

}
