<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Nullable;

interface Formula_NullableInterface {

  /**
   * @return bool
   */
  public function allowsNull(): bool;

}
