<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

abstract class SpecificAdapterZeroBase implements SpecificAdapterInterface {

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }

}
