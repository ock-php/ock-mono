<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Sequence;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

abstract class Formula_SequenceBase implements Formula_SequenceInterface {

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private $itemFormula;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $itemFormula
   */
  public function __construct(FormulaInterface $itemFormula) {
    $this->itemFormula = $itemFormula;
  }

  /**
   * {@inheritdoc}
   */
  public function getItemFormula(): FormulaInterface {
    return $this->itemFormula;
  }
}
