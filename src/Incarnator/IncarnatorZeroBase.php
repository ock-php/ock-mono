<?php
declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

abstract class IncarnatorZeroBase implements IncarnatorInterface {

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }
}
