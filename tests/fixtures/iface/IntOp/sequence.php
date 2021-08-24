<?php

use Donquixote\ObCK\Tests\Fixture\IntOp\IntOp_Add;
use Donquixote\ObCK\Tests\Fixture\IntOp\IntOp_Multiply;
use Donquixote\ObCK\Tests\Fixture\IntOp\IntOp_Sequence;
use Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface;

return static function (): IntOpInterface {
  return new IntOp_Sequence(
    [
      new IntOp_Add(5),
      new IntOp_Multiply(7),
    ]);
};
