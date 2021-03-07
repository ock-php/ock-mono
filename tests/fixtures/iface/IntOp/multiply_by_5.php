<?php

use Donquixote\OCUI\Tests\Fixture\IntOp\IntOp_Multiply;
use Donquixote\OCUI\Tests\Fixture\IntOp\IntOpInterface;

return static function (): IntOpInterface {
  return new IntOp_Multiply(5);
};
