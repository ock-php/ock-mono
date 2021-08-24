<?php

use Donquixote\ObCK\Tests\Fixture\IntOp\IntOp_Multiply;
use Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface;

return static function (): IntOpInterface {
  return new IntOp_Multiply(5);
};
