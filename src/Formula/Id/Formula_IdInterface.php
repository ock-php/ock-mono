<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Id;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Text\TextInterface;

/**
 * Base interface for schemas where the value is id-like (string or integer).
 */
interface Formula_IdInterface extends FormulaInterface {

  /**
   * Gets the option label for a given id.
   *
   * @param string|int $id
   *   The id.
   *
   * @return \Donquixote\OCUI\Text\TextInterface|null
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
