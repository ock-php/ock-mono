<?php /** @noinspection PhpUnused */

use Donquixote\Ock\Tests\Fixture\NumberFormat\NumberFormat_IntRounded;
use Donquixote\Ock\Tests\Fixture\NumberFormat\NumberFormatInterface;

return static function (): NumberFormatInterface {
  return new NumberFormat_IntRounded(NULL);
};
