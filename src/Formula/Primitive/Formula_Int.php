<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Primitive;

class Formula_Int implements Formula_PrimitiveInterface {

  /**
   * {@inheritdoc}
   */
  public function getAllowedTypes(): array {
    return ['int', 'integer'];
  }

}
