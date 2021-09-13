<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Primitive;

use Donquixote\Ock\Core\Formula\FormulaInterface;

interface Formula_PrimitiveInterface extends FormulaInterface {

  /**
   * @return string[]
   */
  public function getAllowedTypes(): array;

}
