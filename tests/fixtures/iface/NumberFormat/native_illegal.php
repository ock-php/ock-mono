<?php

use Donquixote\Ock\Evaluator\Evaluator;
use Donquixote\Ock\Tests\Fixture\NumberFormat\NumberFormat_Native;
use Donquixote\Ock\Tests\Fixture\NumberFormat\NumberFormatInterface;

return static function (): NumberFormatInterface {
  return NumberFormat_Native::create(
    3,
    Evaluator::incompatibleConfiguration(
      'Unknown id \',:\' for id formula.'));
};
