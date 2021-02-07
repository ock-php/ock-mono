<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Sequence;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

abstract class Formula_SequenceBase implements Formula_SequenceInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  private $itemFormula;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $itemFormula
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
