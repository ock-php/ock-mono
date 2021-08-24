<?php

use Donquixote\ObCK\Tests\Fixture\NumberFormat\NumberFormat_Trivial;
use Donquixote\ObCK\Tests\Fixture\NumberFormat\NumberFormatInterface;

return static function (): NumberFormatInterface {
  return new NumberFormat_Trivial();
};
