<?php

use Donquixote\ObCK\Tests\Fixture\NumberFormat\NumberFormat_Native;
use Donquixote\ObCK\Tests\Fixture\NumberFormat\NumberFormatInterface;

return static function (): NumberFormatInterface {
  return NumberFormat_Native::create(3, ',.');
};
