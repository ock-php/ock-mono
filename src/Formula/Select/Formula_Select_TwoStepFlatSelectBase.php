<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\Select;

use Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface;

abstract class Formula_Select_TwoStepFlatSelectBase extends Formula_Select_TwoStepFlatSelectGrandBase {

  /**
   * @var \Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface
   */
  private $idFormula;

  /**
   * @param \Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface $idFormula
   */
  public function __construct(Formula_FlatSelectInterface $idFormula) {
    $this->idFormula = $idFormula;
  }

  /**
   * @return \Donquixote\Ock\Formula\Select\Flat\Formula_FlatSelectInterface
   */
  protected function getIdFormula(): Formula_FlatSelectInterface {
    return $this->idFormula;
  }
}
