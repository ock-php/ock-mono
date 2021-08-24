<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Optional;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Text\TextInterface;

interface Formula_OptionalInterface extends FormulaInterface {

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   *   The non-optional version.
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\ObCK\Text\TextInterface|null
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
