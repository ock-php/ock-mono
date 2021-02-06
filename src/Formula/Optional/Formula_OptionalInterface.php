<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Optional;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Text\TextInterface;

interface Formula_OptionalInterface extends FormulaInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   *   The non-optional version.
   */
  public function getDecorated(): FormulaInterface;

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
