<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Group;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface Formula_GroupInterface extends FormulaInterface {

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface[]
   *   Format: $[$groupItemKey] = $groupItemFormula
   */
  public function getItemFormulas(): array;

  /**
   * @return \Donquixote\ObCK\Text\TextInterface[]
   */
  public function getLabels(): array;

}
