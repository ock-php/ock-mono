<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Tests\Fixture\IntFormat;

/**
 * Adapter from NumberFormat to IntFormat.
 *
 * @obck("trivial", "Trivial - print integer as-is")
 */
class IntFormat_Trivial implements IntFormatInterface {

  /**
   * {@inheritdoc}
   */
  public function format(int $number): string {
    return (string) $number;
  }

}
