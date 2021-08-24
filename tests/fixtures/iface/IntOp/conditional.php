<?php

use Donquixote\ObCK\Tests\Fixture\IntCondition\IntCondition_Odd;
use Donquixote\ObCK\Tests\Fixture\IntOp\IntOp_Add;
use Donquixote\ObCK\Tests\Fixture\IntOp\IntOp_Conditional;
use Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface;

return static function (): IntOpInterface {
  return new IntOp_Conditional(
    new IntCondition_Odd(),
    new IntOp_Add(7),
    NULL);
};
