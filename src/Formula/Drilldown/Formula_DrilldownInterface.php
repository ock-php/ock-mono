<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Drilldown;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\IdToFormula\IdToFormulaInterface;
use Donquixote\OCUI\Formula\Id\Formula_IdInterface;

interface Formula_DrilldownInterface extends FormulaInterface {

  /**
   * @return \Donquixote\OCUI\Formula\Id\Formula_IdInterface
   */
  public function getIdFormula(): Formula_IdInterface;

  /**
   * @return \Donquixote\OCUI\IdToFormula\IdToFormulaInterface
   */
  public function getIdToFormula(): IdToFormulaInterface;

  /**
   * @return string|null
   */
  public function getIdKey(): ?string;

  /**
   * @return string|null
   */
  public function getOptionsKey(): ?string;

}
