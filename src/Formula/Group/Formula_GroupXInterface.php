<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group;

use Donquixote\Ock\Core\Formula\FormulaInterface;

interface Formula_GroupXInterface extends FormulaInterface {

  /**
   * @return \Donquixote\Ock\Formula\Group\Item\GroupFormulaItemInterface[]
   */
  public function getItems(): array;

}
