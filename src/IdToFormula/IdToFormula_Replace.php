<?php
declare(strict_types=1);

namespace Donquixote\Ock\IdToFormula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Nursery\NurseryInterface;

class IdToFormula_Replace implements IdToFormulaInterface {

  private IdToFormulaInterface $decorated;

  /**
   * @var \Donquixote\Ock\Nursery\NurseryInterface
   */
  private NurseryInterface $formulaToAnything;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\IdToFormula\IdToFormulaInterface $decorated
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
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
    catch (IncarnatorException $e) {
      // @todo Log this.
      return NULL;
    }
  }

}
