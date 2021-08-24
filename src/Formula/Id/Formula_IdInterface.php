<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Id;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Text\TextInterface;

/**
 * Base interface for formulas where the value is id-like (string or integer).
 */
interface Formula_IdInterface extends FormulaInterface {

  /**
   * Gets the option label for a given id.
   *
   * @param string|int $id
   *   The id.
   *
   * @return \Donquixote\ObCK\Text\TextInterface|null
   *   The label as a string or stringable object.
   */
  public function idGetLabel($id): ?TextInterface;

  /**
   * Checks if an option exists for a given id.
   *
   * @param string|int $id
   *   The id.
   *
   * @return bool
   */
  public function idIsKnown($id): bool;

}
