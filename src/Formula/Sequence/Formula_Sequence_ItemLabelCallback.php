<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Sequence;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;

class Formula_Sequence_ItemLabelCallback extends Formula_SequenceBase {

  /**
   * @var callable
   */
  private $itemLabelCallback;

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $itemFormula
   * @param callable $itemLabelCallback
   */
  public function __construct(FormulaInterface $itemFormula, callable $itemLabelCallback) {
    parent::__construct($itemFormula);
    $this->itemLabelCallback = $itemLabelCallback;
  }

  /**
   * {@inheritdoc}
   */
  public function deltaGetItemLabel(?int $delta): TextInterface {
    $label = \call_user_func($this->itemLabelCallback, $delta);
    if ($label instanceof TextInterface) {
      return $label;
    }
    if (is_string($label)) {
      return Text::s($label);
    }
    return Text::i($delta);
  }
}
