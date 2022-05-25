<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Sequence;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\Text_Replacements;
use Donquixote\Ock\Text\TextInterface;

class Formula_Sequence_ItemLabelT extends Formula_SequenceBase {

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
    private readonly TextInterface $newItemLabel,
    private readonly TextInterface $itemLabelN,
    private readonly string $placeholder = '!n'
  ) {
    parent::__construct($itemFormula);
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
