<?php

use Donquixote\Ock\Exception\GeneratorException_IncompatibleConfiguration;

// Exception thrown in generator.
return static function () {
  throw new GeneratorException_IncompatibleConfiguration(
    'Text \'150\' fails validation: Value must be no greater than 100..');
};
