<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Boolean;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Text\TextInterface;

interface Formula_BooleanInterface extends FormulaInterface {

  /**
   * Gets a summary for true.
   *
   * @return \Donquixote\OCUI\Text\TextInterface|null
   *   Summary in case the value is true.
   */
  public function getTrueSummary(): ?TextInterface;

  /**
   * @return \Donquixote\OCUI\Text\TextInterface|null
   *   Summary in case the value is true.
   */
  public function getFalseSummary(): ?TextInterface;

}
