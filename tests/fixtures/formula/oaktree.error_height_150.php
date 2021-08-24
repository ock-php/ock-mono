<?php

use Donquixote\ObCK\Evaluator\Evaluator;
use Donquixote\ObCK\Tests\Fixture\Plant\Plant_OakTree;

return new Plant_OakTree(
  Evaluator::expectedConfigButFound(
    '1 errors in text component.',
    '150'));
