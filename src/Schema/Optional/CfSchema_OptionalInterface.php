<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Optional;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Text\TextInterface;

interface CfSchema_OptionalInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   *   The non-optional version.
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\Cf\Text\TextInterface|null
   */
  public function getEmptySummary(): ?TextInterface;

  /**
   * @return string
   *   Typically 'NULL'.
   *
   * @todo Does this need a helper?
   */
  public function getEmptyPhp(): string;

}
