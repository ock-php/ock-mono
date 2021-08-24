<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

class IdToFormula_Fixed implements IdToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface[]
   */
  private $formulas;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface[] $formulas
   */
  public function __construct(array $formulas) {
    $this->formulas = $formulas;
  }

  /**
   * @param string $id
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *
   * @return \Donquixote\ObCK\IdToFormula\IdToFormula_Fixed
   */
  public function withFormula(string $id, FormulaInterface $formula): IdToFormula_Fixed {
    $clone = clone $this;
    $clone->formulas[$id] = $formula;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula($id): ?FormulaInterface {

    return $this->formulas[$id] ?? null;
  }
}
