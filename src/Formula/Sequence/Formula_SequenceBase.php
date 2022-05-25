<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Sequence;

use Donquixote\Ock\Core\Formula\FormulaInterface;

abstract class Formula_SequenceBase implements Formula_SequenceInterface {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $itemFormula
   */
  public function __construct(
    private readonly FormulaInterface $itemFormula,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getItemFormula(): FormulaInterface {
    return $this->itemFormula;
  }

}
