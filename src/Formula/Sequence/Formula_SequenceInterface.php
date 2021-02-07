<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Sequence;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Translator\TranslatorInterface;

interface Formula_SequenceInterface extends FormulaInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function getItemFormula(): FormulaInterface;

  /**
   * Gets a label for the nth sequence item.
   *
   * @param int|null $delta
   *   Index of the sequence item, or NULL for the "new item" item.
   * @param \Donquixote\OCUI\Translator\TranslatorInterface $helper
   *
   * @return string
   */
  public function deltaGetItemLabel(?int $delta, TranslatorInterface $helper): string;

}
