<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group;

use Donquixote\Ock\Core\Formula\FormulaInterface;

interface Formula_GroupInterface extends FormulaInterface {

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface[]
   *   Format: $[$groupItemKey] = $groupItemFormula
   */
  public function getItemFormulas(): array;

  /**
   * @return \Donquixote\Ock\Text\TextInterface[]
   */
  public function getLabels(): array;

}
