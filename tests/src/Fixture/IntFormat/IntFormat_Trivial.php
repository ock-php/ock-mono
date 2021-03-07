<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Tests\Fixture\IntFormat;

use Donquixote\OCUI\Tests\Fixture\NumberFormat\NumberFormatInterface;

/**
 * Adapter from NumberFormat to IntFormat.
 *
 * @ocui("trivial", "Trival - print integer as-is")
 */
class IntFormat_Trivial implements IntFormatInterface {

  /**
   * {@inheritdoc}
   */
  public function format(int $number): string {
    return (string) $number;
  }

}
