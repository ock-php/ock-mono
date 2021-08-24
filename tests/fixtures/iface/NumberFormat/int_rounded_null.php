<?php

use Donquixote\ObCK\Tests\Fixture\NumberFormat\NumberFormat_IntRounded;
use Donquixote\ObCK\Tests\Fixture\NumberFormat\NumberFormatInterface;

return static function (): NumberFormatInterface {
  return new NumberFormat_IntRounded(NULL);
};
