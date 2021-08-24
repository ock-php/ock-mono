<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

class IdToFormula_Buffer implements IdToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface[]
   */
  private $buffer = [];

  /**
   * @param \Donquixote\ObCK\IdToFormula\IdToFormulaInterface $decorated
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
