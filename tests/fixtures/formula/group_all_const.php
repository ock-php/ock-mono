<?php

use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\ValueProvider\Formula_ValueProvider_FixedPhp;

// Group formula without any options.
return Formula::group()
  ->addOptionless('a', new Formula_ValueProvider_FixedPhp('1'))
  ->build();
