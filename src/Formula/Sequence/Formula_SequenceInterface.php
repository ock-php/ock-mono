<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Sequence;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Text\TextInterface;
use Donquixote\ObCK\Translator\TranslatorInterface;

interface Formula_SequenceInterface extends FormulaInterface {

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function getItemFormula(): FormulaInterface;

  /**
   * Gets a label for the nth sequence item.
   *
   * @param int|null $delta
   *   Index of the sequence item, or NULL for the "new item" item.
   *
   * @return \Donquixote\ObCK\Text\TextInterface
   */
  public function deltaGetItemLabel(?int $delta): TextInterface;

}
