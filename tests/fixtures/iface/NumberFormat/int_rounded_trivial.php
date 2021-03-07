<?php

use Donquixote\OCUI\Tests\Fixture\IntFormat\IntFormat_Trivial;
use Donquixote\OCUI\Tests\Fixture\NumberFormat\NumberFormat_IntRounded;
use Donquixote\OCUI\Tests\Fixture\NumberFormat\NumberFormatInterface;

return static function (): NumberFormatInterface {
  return new NumberFormat_IntRounded(
    new IntFormat_Trivial());
};
