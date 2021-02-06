<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Optional;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Text\TextInterface;

interface CfSchema_OptionalInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   *   The non-optional version.
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\OCUI\Text\TextInterface|null
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
