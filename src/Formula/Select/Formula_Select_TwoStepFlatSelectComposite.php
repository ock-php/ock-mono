<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\IdToFormula\IdToFormulaInterface;
use Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface;

class Formula_Select_TwoStepFlatSelectComposite extends Formula_Select_TwoStepFlatSelectBase {

  /**
   * @var \Donquixote\OCUI\IdToFormula\IdToFormulaInterface
   */
  private $idToSubFormula;

  /**
   * @param \Donquixote\OCUI\Formula\Select\Flat\Formula_FlatSelectInterface $idFormula
   * @param \Donquixote\OCUI\IdToFormula\IdToFormulaInterface $idToSubFormula
   */
  public function __construct(
    Formula_FlatSelectInterface $idFormula,
    IdToFormulaInterface $idToSubFormula
  ) {
    parent::__construct($idFormula);
    $this->idToSubFormula = $idToSubFormula;
  }

  /**
   * @param string $id
   *
   * @return Formula_FlatSelectInterface|null
   */
  protected function idGetSubFormula(string $id): ?Formula_FlatSelectInterface {

    $subFormula = $this->idToSubFormula->idGetFormula($id);

    if (!$subFormula instanceof Formula_FlatSelectInterface) {
      return NULL;
    }

    return $subFormula;
  }
}
