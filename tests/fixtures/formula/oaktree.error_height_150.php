<?php

use Donquixote\OCUI\Evaluator\Evaluator;
use Donquixote\OCUI\Tests\Fixture\Plant\Plant_OakTree;

return new Plant_OakTree(
  Evaluator::expectedConfigButFound(
    '1 errors in text component.',
    '150'));
