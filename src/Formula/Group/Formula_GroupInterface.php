<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Group;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface Formula_GroupInterface extends FormulaInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   *   Format: $[$groupItemKey] = $groupItemFormula
   */
  public function getItemFormulas(): array;

  /**
   * @return \Donquixote\OCUI\Text\TextInterface[]
   */
  public function getLabels(): array;

}
