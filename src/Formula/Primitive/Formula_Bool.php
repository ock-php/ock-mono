<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Primitive;

class Formula_Bool implements Formula_PrimitiveInterface {

  /**
   * {@inheritdoc}
   */
  public function getAllowedTypes(): array {
    return ['bool'];
  }

}
