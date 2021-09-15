<?php

declare(strict_types=1);

namespace Donquixote\Ock\Evaluator;

interface EvaluatorInterface {

  /**
   * @param mixed $conf
   *   Configuration.
   *
   * @return mixed
   *   Object or value.
   */
  public function confGetValue($conf);

}
