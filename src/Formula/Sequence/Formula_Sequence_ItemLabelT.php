<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Sequence;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\Text_Replacements;
use Donquixote\Ock\Text\TextInterface;

class Formula_Sequence_ItemLabelT extends Formula_SequenceBase {

  /**
   * @var \Donquixote\Ock\Text\TextInterface
   */
  private $newItemLabel;

  /**
   * @var \Donquixote\Ock\Text\TextInterface
   */
  private $itemLabelN;

  /**
   * @var string
   */
  private $placeholder;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $itemFormula
   * @param \Donquixote\Ock\Text\TextInterface $newItemLabel
   * @param \Donquixote\Ock\Text\TextInterface $itemLabelN
   * @param string $placeholder
   */
  public function __construct(
    FormulaInterface $itemFormula,
    TextInterface $newItemLabel,
    TextInterface $itemLabelN,
    $placeholder = '!n'
  ) {
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
