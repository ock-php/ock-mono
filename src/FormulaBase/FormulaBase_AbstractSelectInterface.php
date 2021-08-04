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
   * Gets named select optgroups.
   *
   * @return \Donquixote\OCUI\Text\TextInterface[]
   *   Format: $[$group_id] = $group_label.
   */
  public function getOptGroups(): array;

  /**
   * Gets select options in a group.
   *
   * @param string|null $group_id
   *   Id of the optgroup, or NULL for top-level options.
   *
   * @return \Donquixote\OCUI\Text\TextInterface[]
   *   Format: $[$value] = $label.
   */
  public function getOptions(?string $group_id): array;

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
