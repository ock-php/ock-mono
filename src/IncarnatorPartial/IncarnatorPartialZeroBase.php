<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

abstract class IncarnatorPartialZeroBase implements IncarnatorPartialInterface {

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }

}
