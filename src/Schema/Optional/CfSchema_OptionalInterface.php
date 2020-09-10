<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Optional;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

interface CfSchema_OptionalInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   *   The non-optional version.
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return string|null
   *
   * @todo Does this need a helper?
   */
  public function getEmptySummary(): ?string;

  /**
   * @return mixed
   *   Typically NULL.
   *
   * @todo Does this need a helper?
   */
  public function getEmptyValue();

  /**
   * @return string
   *   Typically 'NULL'.
   *
   * @todo Does this need a helper?
   */
  public function getEmptyPhp(): string;

}
