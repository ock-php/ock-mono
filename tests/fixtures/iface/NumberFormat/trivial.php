<?php

use Donquixote\OCUI\Tests\Fixture\NumberFormat\NumberFormat_Trivial;
use Donquixote\OCUI\Tests\Fixture\NumberFormat\NumberFormatInterface;

return static function (): NumberFormatInterface {
  return new NumberFormat_Trivial();
};
