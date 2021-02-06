<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Id;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Text\TextInterface;

/**
 * Base interface for schemas where the value is id-like (string or integer).
 */
interface CfSchema_IdInterface extends CfSchemaInterface {

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
