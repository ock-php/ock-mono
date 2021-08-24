<?php

namespace Donquixote\ObCK\FormulasByType;

interface FormulasByTypeInterface {

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface[]
   */
  public function getFormulasByType(): array;

}
