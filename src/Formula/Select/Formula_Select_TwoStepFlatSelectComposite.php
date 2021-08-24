<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Select;

use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Donquixote\ObCK\Formula\Select\Flat\Formula_FlatSelectInterface;

class Formula_Select_TwoStepFlatSelectComposite extends Formula_Select_TwoStepFlatSelectBase {

  /**
   * @var \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  private $idToSubFormula;

  /**
   * @param \Donquixote\ObCK\Formula\Select\Flat\Formula_FlatSelectInterface $idFormula
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $idToSubFormula
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
