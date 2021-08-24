<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

class IdToFormula_AlwaysTheSame implements IdToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProvider_FixedValue
   */
  private $sameFormula;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $sameFormula
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
