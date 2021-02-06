<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Sequence;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;

class CfSchema_Sequence_ItemLabelT extends CfSchema_SequenceBase {

  /**
   * @var string
   */
  private $newItemLabel;

  /**
   * @var string
   */
  private $itemLabelN;

  /**
   * @var string
   */
  private $placeholder;

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $itemSchema
   * @param string $newItemLabel
   * @param string $itemLabelN
   * @param string $placeholder
   */
  public function __construct(CfSchemaInterface $itemSchema, string $newItemLabel, string $itemLabelN, $placeholder = '!n') {
    parent::__construct($itemSchema);
    $this->newItemLabel = $newItemLabel;
    $this->itemLabelN = $itemLabelN;
    $this->placeholder = $placeholder;
  }

  /**
   * {@inheritdoc}
   */
  public function deltaGetItemLabel(?int $delta, TranslatorInterface $helper): string {

    return (NULL === $delta)
      ? $helper->translate($this->newItemLabel)
      : $helper->translate(
        $this->itemLabelN,
        [$this->placeholder => $delta]);
  }
}
