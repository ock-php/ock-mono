<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Tests\Fixture\NumberFormat;

/**
 * Adapter from NumberFormat to IntFormat.
 *
 * @ocui("trivial", "Trival - print number as-is")
 */
class NumberFormat_Trivial implements NumberFormatInterface {

  /**
   * {@inheritdoc}
   */
  public function format($number): string {
    return (string) $number;
  }

}
