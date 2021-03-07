<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaBase;

use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\TextToMarkup\TextToMarkupInterface;

/**
 * This is a base interface, which by itself does NOT extend FormulaInterface.
 */
interface FormulaBase_AbstractSelectInterface {

  /**
   * Gets select options in groups.
   *
   * @param \Donquixote\OCUI\TextToMarkup\TextToMarkupInterface $textToMarkup
   *   Service to translate strings.
   *
   * @return \Donquixote\OCUI\Text\TextInterface[][]
   *   Format: $[$group_label][$option_id] = $option_label,
   *   with $group_label === '' for toplevel options.
   */
  public function getGroupedOptions(TextToMarkupInterface $textToMarkup): array;

  /**
   * @param string|int $id
   *
   * @return \Donquixote\OCUI\Text\TextInterface|null
   */
  public function idGetLabel($id): ?TextInterface;

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown($id): bool;

}
