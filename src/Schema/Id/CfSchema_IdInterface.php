<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Id;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Text\TextInterface;

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
   * @return \Donquixote\Cf\Text\TextInterface|null
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
