<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Sequence;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\Text_Replacements;
use Donquixote\OCUI\Text\TextInterface;

class Formula_Sequence_ItemLabelT extends Formula_SequenceBase {

  /**
   * @var \Donquixote\OCUI\Text\TextInterface
   */
  private $newItemLabel;

  /**
   * @var \Donquixote\OCUI\Text\TextInterface
   */
  private $itemLabelN;

  /**
   * @var string
   */
  private $placeholder;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $itemFormula
   * @param \Donquixote\OCUI\Text\TextInterface $newItemLabel
   * @param \Donquixote\OCUI\Text\TextInterface $itemLabelN
   * @param string $placeholder
   */
  public function __construct(FormulaInterface $itemFormula, TextInterface $newItemLabel, TextInterface $itemLabelN, $placeholder = '!n') {
    parent::__construct($itemFormula);
    $this->newItemLabel = $newItemLabel;
    $this->itemLabelN = $itemLabelN;
    $this->placeholder = $placeholder;
  }

  /**
   * {@inheritdoc}
   */
  public function deltaGetItemLabel(?int $delta): TextInterface {
    return (NULL === $delta)
      ? $this->newItemLabel
      : new Text_Replacements($this->itemLabelN, [
        $this->placeholder => Text::i($delta),
      ]);
  }
}
