<?php
declare(strict_types=1);

namespace Donquixote\Ock\IdToFormula;

use Donquixote\Ock\Core\Formula\FormulaInterface;

class IdToFormula_Buffer implements IdToFormulaInterface {

  /**
   * @var \Donquixote\Ock\IdToFormula\IdToFormulaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Ock\Core\Formula\FormulaInterface[]
   */
  private $buffer = [];

  /**
   * @param \Donquixote\Ock\IdToFormula\IdToFormulaInterface $decorated
   */
  public function __construct(IdToFormulaInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula($id): ?FormulaInterface {
    // @todo Optimize with isset()? But allow NULL values?
    return array_key_exists($id, $this->buffer)
      ? $this->buffer[$id]
      : $this->buffer[$id] = $this->decorated->idGetFormula($id);
  }
}
