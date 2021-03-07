<?php

use Donquixote\OCUI\Tests\Fixture\IntFormat\IntFormat_NumberFormat;
use Donquixote\OCUI\Tests\Fixture\IntFormat\IntFormatInterface;
use Donquixote\OCUI\Tests\Fixture\NumberFormat\NumberFormat_Native;

return static function (): IntFormatInterface {
  return new IntFormat_NumberFormat(
    NumberFormat_Native::create(3, ',.'));
};
