<?php

use Donquixote\Ock\Formula\Formula;

// Group formula without any options.
return Formula::group()
  ->addScalar('a', 1)
  ->buildGroupValFormula();
