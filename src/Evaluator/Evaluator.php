<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Evaluator;

use Donquixote\OCUI\Exception\EvaluatorException_IncompatibleConfiguration;

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
    $message = $expected . ', found ' . self::formatConf($conf);
    throw new EvaluatorException_IncompatibleConfiguration($message);
  }

  /**
   * @param mixed $conf
   *
   * @return string
   */
  private static function formatConf($conf): string {
    switch ($type = gettype($conf)) {
      case 'object':
        return get_class($conf) . ' object';
      case 'array':
        return $conf ? 'array(..)' : 'array()';
      case 'resource':
        return 'resource';
      case 'integer':
        return '(int)' . $conf;
      default:
        return var_export($conf, TRUE);
    }
  }

}
