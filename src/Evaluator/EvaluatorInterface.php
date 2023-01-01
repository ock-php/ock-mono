<?php

declare(strict_types = 1);

namespace Donquixote\DID\Evaluator;

interface EvaluatorInterface {

  /**
   * @param mixed $definition
   *
   * @return mixed
   */
  public function evaluate(mixed $definition): mixed;

}
