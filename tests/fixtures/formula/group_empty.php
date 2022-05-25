<?php

use Donquixote\Ock\Formula\Formula;

// Group formula without any options.
return Formula::group()
  ->construct(\stdClass::class);
