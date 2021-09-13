<?php
declare(strict_types=1);

namespace Donquixote\Ock\FormulaBase;

use Donquixote\Ock\Text\TextInterface;

/**
 * This is a base interface, which by itself does NOT extend FormulaInterface.
 *
 * @todo Do we really need TextInterface here?
 */
interface FormulaBase_AbstractSelectInterface {

  /**
   * Gets named select optgroups.
   *
   * @return \Donquixote\Ock\Text\TextInterface[]
   *   Format: $[$group_id] = $group_label.
   */
  public function getOptGroups(): array;

  /**
   * Gets select options in a group.
   *
   * @param string|null $group_id
   *   Id of the optgroup, or NULL for top-level options.
   *
   * @return \Donquixote\Ock\Text\TextInterface[]
   *   Format: $[$value] = $label.
   */
  public function getOptions(?string $group_id): array;

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Ock\Text\TextInterface|null
   */
  public function idGetLabel($id): ?TextInterface;

  /**
   * @param string|int $id
   *
   * @return bool
   *
   * @see \Donquixote\Ock\Formula\Id\Formula_IdInterface::idIsKnown()
   */
  public function idIsKnown($id): bool;

}
