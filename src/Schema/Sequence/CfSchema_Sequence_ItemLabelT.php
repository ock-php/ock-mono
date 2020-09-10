<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Sequence;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Translator\TranslatorInterface;

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
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $itemSchema
   * @param string $newItemLabel
   * @param string $itemLabelN
   * @param string $placeholder
   */
  public function __construct(CfSchemaInterface $itemSchema, $newItemLabel, $itemLabelN, $placeholder = '!n') {
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
