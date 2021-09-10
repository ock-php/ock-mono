<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Drilldown;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Donquixote\ObCK\Formula\Id\Formula_IdInterface;

interface Formula_DrilldownInterface extends FormulaInterface {

  /**
   * @return \Donquixote\ObCK\Formula\Id\Formula_IdInterface
   */
  public function getIdFormula(): Formula_IdInterface;

  /**
   * @return \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  public function getIdToFormula(): IdToFormulaInterface;

  /**
   * @return string
   */
  public function getIdKey(): string;

  /**
   * @return string|null
   */
  public function getOptionsKey(): ?string;

  /**
   * @return bool
   */
  public function allowsNull(): bool;

}
