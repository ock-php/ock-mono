<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Exception\FormulaToAnythingException;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;

class IdToFormula_Replace implements IdToFormulaInterface {

  private IdToFormulaInterface $decorated;

  /**
   * @var \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface
   */
  private FormulaToAnythingInterface $formulaToAnything;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $decorated
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   */
  public function __construct(IdToFormulaInterface $decorated, FormulaToAnythingInterface $formulaToAnything) {
    $this->decorated = $decorated;
    $this->formulaToAnything = $formulaToAnything;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula(string $id): ?FormulaInterface {
    $formula = $this->decorated->idGetFormula($id);
    if ($formula === NULL) {
      return NULL;
    }
    try {
      return Formula::replace($formula, $this->formulaToAnything);
    }
    catch (FormulaToAnythingException $e) {
      // @todo Log this.
      return NULL;
    }
  }

}
