<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Tests\Fixture\NumberFormat;

/**
 * Adapter from NumberFormat to IntFormat.
 *
 * @obck("trivial", "Trival - print number as-is")
 */
class NumberFormat_Trivial implements NumberFormatInterface {

  /**
   * {@inheritdoc}
   */
  public function format($number): string {
    return (string) $number;
  }

}
