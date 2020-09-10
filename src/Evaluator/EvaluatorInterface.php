<?php
declare(strict_types=1);

namespace Donquixote\Cf\Evaluator;

interface EvaluatorInterface {

  /**
   * @param mixed $conf
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function confGetValue($conf);

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf): string;

}
