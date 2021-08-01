<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Evaluator;

use Donquixote\OCUI\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\OCUI\Util\MessageUtil;

class Evaluator {

  /**
   * Throws an exception for incompatible configuration.
   *
   * @param string $message
   *   Message describing the problem.
   *
   * @return mixed
   *
   * @throws \Donquixote\OCUI\Exception\EvaluatorException_IncompatibleConfiguration
   */
  public static function incompatibleConfiguration(string $message) {
    throw new EvaluatorException_IncompatibleConfiguration($message);
  }

  /**
   * Throws an exception for incompatible configuration.
   *
   * @param string $expected
   *   Message describing the expected configuration.
   * @param mixed $conf
   *   Failing configuration.
   *
   * @return mixed
   *
   * @throws \Donquixote\OCUI\Exception\EvaluatorException_IncompatibleConfiguration
   */
  public static function expectedConfigButFound(string $expected, $conf) {
    $message = $expected . ', found ' . MessageUtil::formatValue($conf);
    throw new EvaluatorException_IncompatibleConfiguration($message);
  }

}
