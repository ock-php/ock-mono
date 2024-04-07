<?php

declare(strict_types = 1);

/** @noinspection PhpUnused */

use Donquixote\Ock\Exception\GeneratorException_IncompatibleConfiguration;

// Exception thrown in generator.
return static function () {
  throw new GeneratorException_IncompatibleConfiguration(
    'Expected an array, found 150.');
};
