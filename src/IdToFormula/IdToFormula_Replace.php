<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Exception\FormulaToAnythingException;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Nursery\NurseryInterface;

class IdToFormula_Replace implements IdToFormulaInterface {

  private IdToFormulaInterface $decorated;

  /**
   * @var \Donquixote\ObCK\Nursery\NurseryInterface
   */
  private NurseryInterface $formulaToAnything;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $decorated
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   */
  public function __construct(IdToFormulaInterface $decorated, NurseryInterface $formulaToAnything) {
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
