<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Sequence;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;

class CfSchema_Sequence_ItemLabelCallback extends CfSchema_SequenceBase {

  /**
   * @var callable
   */
  private $itemLabelCallback;

  /**
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $itemSchema
   * @param callable $itemLabelCallback
   */
  public function __construct(CfSchemaInterface $itemSchema, callable $itemLabelCallback) {
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
