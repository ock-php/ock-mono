<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Evaluator;

interface EvaluatorInterface {

  /**
   * @param $conf
   *   Configuration.
   *
   * @return mixed
   *   Object or value.
   */
  public function confGetValue($conf);

}
