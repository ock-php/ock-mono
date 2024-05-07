<?php

declare(strict_types=1);

use Donquixote\Ock\Tests\Fixture\IntFormat\IntFormat_NumberFormat;
use Donquixote\Ock\Tests\Fixture\IntFormat\IntFormatInterface;
use Donquixote\Ock\Tests\Fixture\NumberFormat\NumberFormat_Native;

return static function (): IntFormatInterface {
  return new IntFormat_NumberFormat(
    NumberFormat_Native::create(3, ',.'),
  );
};
