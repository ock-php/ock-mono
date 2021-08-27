<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaBase;

use Donquixote\ObCK\Text\TextInterface;

/**
 * This is a base interface, which by itself does NOT extend FormulaInterface.
 */
interface FormulaBase_AbstractSelectInterface {

  /**
   * Gets named select optgroups.
   *
   * @return \Donquixote\ObCK\Text\TextInterface[]
   *   Format: $[$group_id] = $group_label.
   */
  public function getOptGroups(): array;

  /**
   * Gets select options in a group.
   *
   * @param string|null $group_id
   *   Id of the optgroup, or NULL for top-level options.
   *
   * @return \Donquixote\ObCK\Text\TextInterface[]
   *   Format: $[$value] = $label.
   */
  public function getOptions(?string $group_id): array;

  /**
   * @param string|int $id
   *
   * @return \Donquixote\ObCK\Text\TextInterface|null
   */
  public function idGetLabel($id): ?TextInterface;

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown($id): bool;

}
