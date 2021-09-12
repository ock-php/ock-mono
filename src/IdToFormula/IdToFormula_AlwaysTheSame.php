<?php
declare(strict_types=1);

namespace Donquixote\Ock\IdToFormula;

use Donquixote\Ock\Core\Formula\FormulaInterface;

class IdToFormula_AlwaysTheSame implements IdToFormulaInterface {

  /**
   * @var \Donquixote\Ock\Formula\ValueProvider\Formula_ValueProvider_FixedPhp
   */
  private $sameFormula;

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $sameFormula
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
