<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Primitive;

class Formula_Float implements Formula_PrimitiveInterface {

  /**
   * {@inheritdoc}
   */
  public function getAllowedTypes(): array {
    return ['float'];
  }

}
