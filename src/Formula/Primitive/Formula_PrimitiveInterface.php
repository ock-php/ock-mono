<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Primitive;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface Formula_PrimitiveInterface extends FormulaInterface {

  /**
   * {@inheritdoc}
   */
  public function getAllowedTypes(): array;

}
