<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Nursery\Cradle;

abstract class CradleZeroBase implements CradleInterface {

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }
}
