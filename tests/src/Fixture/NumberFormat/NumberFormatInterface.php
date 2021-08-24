<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Tests\Fixture\NumberFormat;

interface NumberFormatInterface {

  /**
   * Formats a number as a string.
   *
   * @param int|float $number
   *   Number to format.
   *
   * @return string
   *   Formatted string.
   */
  public function format($number): string;

}
