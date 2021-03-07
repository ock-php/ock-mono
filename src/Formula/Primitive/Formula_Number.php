<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Primitive;

class Formula_Number implements Formula_PrimitiveInterface {

  /**
   * {@inheritdoc}
   */
  public function getAllowedTypes(): array {
    return ['int', 'float', 'integer'];
  }

}
