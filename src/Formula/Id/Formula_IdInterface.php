<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Id;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Text\TextInterface;

/**
 * Base interface for formulas where the value is id-like (string or integer).
 *
 * @todo Do we really need TextInterface here?
 *
 * @todo
 */
interface Formula_IdInterface extends FormulaInterface {

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
