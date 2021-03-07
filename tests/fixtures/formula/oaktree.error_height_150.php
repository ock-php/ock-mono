<?php

use Donquixote\OCUI\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\OCUI\Tests\Fixture\Plant\Plant_OakTree;

return new Plant_OakTree(
  // @todo Fix the generated code manually.
  call_user_func(
    static function () {
      throw new EvaluatorException_IncompatibleConfiguration('1 errors in text component.');
    }));
