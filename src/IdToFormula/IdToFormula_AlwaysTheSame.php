<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToFormula;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class IdToFormula_AlwaysTheSame implements IdToFormulaInterface {

  /**
   * @var \Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProvider_FixedValue
   */
  private $sameFormula;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $sameFormula
   */
  public function __construct(FormulaInterface $sameFormula) {
    $this->sameFormula = $sameFormula;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula($id): ?FormulaInterface {
    return $this->sameFormula;
  }
}
