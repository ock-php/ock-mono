<?php

use Donquixote\OCUI\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\OCUI\Tests\Fixture\NumberFormat\NumberFormat_Native;
use Donquixote\OCUI\Tests\Fixture\NumberFormat\NumberFormatInterface;

return static function (): NumberFormatInterface {
  return NumberFormat_Native::create(
    3,
    // @todo Fix the generated code manually.
    call_user_func(
      static function () {
        throw new EvaluatorException_IncompatibleConfiguration('Unknown id \',:\' for id formula.');
      }));
};
