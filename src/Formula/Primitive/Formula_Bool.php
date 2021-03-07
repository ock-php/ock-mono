<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Primitive;

class Formula_Bool implements Formula_PrimitiveInterface {

  /**
   * {@inheritdoc}
   */
  public function getAllowedTypes(): array {
    return ['bool'];
  }

}
