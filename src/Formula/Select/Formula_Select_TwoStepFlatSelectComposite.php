<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Select;

use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;
use Donquixote\Ock\IdToFormula\IdToFormulaInterface;

class Formula_Select_TwoStepFlatSelectComposite extends Formula_Select_TwoStepFlatSelectBase {

  /**
   * @var \Donquixote\Ock\IdToFormula\IdToFormulaInterface
   */
  private $idToSubFormula;

  /**
   * @param \Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface $idFormula
   * @param \Donquixote\Ock\IdToFormula\IdToFormulaInterface $idToSubFormula
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
   * @return \Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface|null
   */
  protected function idGetSubFormula(string $id): ?Formula_FlatSelectInterface {

    $subFormula = $this->idToSubFormula->idGetFormula($id);

    if (!$subFormula instanceof Formula_FlatSelectInterface) {
      return NULL;
    }

    return $subFormula;
  }

}
