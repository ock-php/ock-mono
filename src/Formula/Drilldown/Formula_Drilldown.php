<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Drilldown;

use Donquixote\OCUI\IdToFormula\IdToFormulaInterface;
use Donquixote\OCUI\Formula\Id\Formula_IdInterface;

class Formula_Drilldown extends Formula_Drilldown_CustomKeysBase {

  /**
   * @var \Donquixote\OCUI\Formula\Id\Formula_IdInterface
   */
  private $idFormula;

  /**
   * @var \Donquixote\OCUI\IdToFormula\IdToFormulaInterface
   */
  private $idToFormula;

  /**
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $idFormula
   * @param \Donquixote\OCUI\IdToFormula\IdToFormulaInterface $idToFormula
   *
   * @return self
   */
  public static function create(Formula_IdInterface $idFormula, IdToFormulaInterface $idToFormula): Formula_Drilldown {
    return new self($idFormula, $idToFormula);
  }

  /**
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $idFormula
   * @param \Donquixote\OCUI\IdToFormula\IdToFormulaInterface $idToFormula
   */
  public function __construct(Formula_IdInterface $idFormula, IdToFormulaInterface $idToFormula) {
    $this->idFormula = $idFormula;
    $this->idToFormula = $idToFormula;
  }

  /**
   * {@inheritdoc}
   */
  public function getIdFormula(): Formula_IdInterface {
    return $this->idFormula;
  }

  /**
   * {@inheritdoc}
   */
  public function getIdToFormula(): IdToFormulaInterface {
    return $this->idToFormula;
  }
}
