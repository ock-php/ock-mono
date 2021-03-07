<?php

namespace Donquixote\OCUI\FormulasByType;

interface FormulasByTypeInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   */
  public function getFormulasByType(): array;

}
