<?php

use Donquixote\Ock\Evaluator\Evaluator;
use Donquixote\Ock\Tests\Fixture\Plant\Plant_OakTree;

return new Plant_OakTree(
  Evaluator::expectedConfigButFound(
    '1 errors in text component.',
    '150'));
