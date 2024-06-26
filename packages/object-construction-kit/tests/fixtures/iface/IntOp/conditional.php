<?php

declare(strict_types=1);

use Ock\Ock\Tests\Fixture\IntCondition\IntCondition_Odd;
use Ock\Ock\Tests\Fixture\IntOp\IntOp_Add;
use Ock\Ock\Tests\Fixture\IntOp\IntOp_Conditional;
use Ock\Ock\Tests\Fixture\IntOp\IntOpInterface;

return static function (): IntOpInterface {
  return new IntOp_Conditional(
    new IntCondition_Odd(),
    new IntOp_Add(7),
    NULL,
  );
};
