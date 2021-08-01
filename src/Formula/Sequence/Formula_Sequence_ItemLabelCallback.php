<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Sequence;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;

class Formula_Sequence_ItemLabelCallback extends Formula_SequenceBase {

  /**
   * @var callable
   */
  private $itemLabelCallback;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $itemFormula
   * @param callable $itemLabelCallback
   */
  public function __construct(FormulaInterface $itemFormula, callable $itemLabelCallback) {
    parent::__construct($itemFormula);
    $this->itemLabelCallback = $itemLabelCallback;
  }

  /**
   * {@inheritdoc}
   */
  public function deltaGetItemLabel(?int $delta): \Donquixote\OCUI\Text\TextInterface {
    $label = \call_user_func($this->itemLabelCallback, $delta, $helper);
    if (!is_string($label)) {
      return '' . $delta;
    }
    return $label;
  }
}
