<?php

use Donquixote\OCUI\Tests\Fixture\NumberFormat\NumberFormat_Native;
use Donquixote\OCUI\Tests\Fixture\NumberFormat\NumberFormatInterface;

return static function (): NumberFormatInterface {
  return NumberFormat_Native::create(3, ',.');
};
