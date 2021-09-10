<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Primitive;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface Formula_PrimitiveInterface extends FormulaInterface {

  /**
   * @return string[]
   */
  public function getAllowedTypes(): array;

}
