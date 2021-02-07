<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Sequence;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;

class Formula_Sequence_ItemLabelT extends Formula_SequenceBase {

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
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $itemFormula
   * @param string $newItemLabel
   * @param string $itemLabelN
   * @param string $placeholder
   */
  public function __construct(FormulaInterface $itemFormula, string $newItemLabel, string $itemLabelN, $placeholder = '!n') {
    parent::__construct($itemFormula);
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
