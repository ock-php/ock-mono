<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Sequence;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;

class CfSchema_Sequence_ItemLabelCallback extends CfSchema_SequenceBase {

  /**
   * @var callable
   */
  private $itemLabelCallback;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $itemSchema
   * @param callable $itemLabelCallback
   */
  public function __construct(FormulaInterface $itemSchema, callable $itemLabelCallback) {
    parent::__construct($itemSchema);
    $this->itemLabelCallback = $itemLabelCallback;
  }

  /**
   * {@inheritdoc}
   */
  public function deltaGetItemLabel(?int $delta, TranslatorInterface $helper): string {
    $label = \call_user_func($this->itemLabelCallback, $delta, $helper);
    if (!is_string($label)) {
      return '' . $delta;
    }
    return $label;
  }
}
