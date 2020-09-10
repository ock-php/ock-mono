<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Sequence;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Translator\TranslatorInterface;

class CfSchema_Sequence_ItemLabelCallback extends CfSchema_SequenceBase {

  /**
   * @var callable
   */
  private $itemLabelCallback;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $itemSchema
   * @param callable $itemLabelCallback
   */
  public function __construct(CfSchemaInterface $itemSchema, $itemLabelCallback) {
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
