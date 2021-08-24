<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Boolean;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Text\TextInterface;

interface Formula_BooleanInterface extends FormulaInterface {

  /**
   * Gets a summary for true.
   *
   * @return \Donquixote\ObCK\Text\TextInterface|null
   *   Summary in case the value is true.
   */
  public function getTrueSummary(): ?TextInterface;

  /**
   * @return \Donquixote\ObCK\Text\TextInterface|null
   *   Summary in case the value is true.
   */
  public function getFalseSummary(): ?TextInterface;

}
