<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Sequence;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\Text_Replacements;
use Donquixote\ObCK\Text\TextInterface;

class Formula_Sequence_ItemLabelT extends Formula_SequenceBase {

  /**
   * @var \Donquixote\ObCK\Text\TextInterface
   */
  private $newItemLabel;

  /**
   * @var \Donquixote\ObCK\Text\TextInterface
   */
  private $itemLabelN;

  /**
   * @var string
   */
  private $placeholder;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $itemFormula
   * @param \Donquixote\ObCK\Text\TextInterface $newItemLabel
   * @param \Donquixote\ObCK\Text\TextInterface $itemLabelN
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
