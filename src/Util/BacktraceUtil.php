<?php
declare(strict_types=1);

namespace Drupal\cu\Util;

use Donquixote\Nicetrace\Util\NicetraceUtil;

final class BacktraceUtil extends UtilBase {

  /**
   * @param \Exception $e
   *
   * @return array[]
   */
  public static function exceptionGetRelativeNicetrace(\Exception $e): array {
    $relative_backtrace = self::traceDiff($e->getTrace());
    return self::nicetrace($relative_backtrace);
  }

  /**
   * @param array[] $trace
   * @param array[] $othertrace
   *   Trace to subtract from the first trace.
   *
   * @return array[]
   *   Part of $trace that is not shared in $othertrace.
   */
  public static function traceDiff(array $trace, array $othertrace = NULL): array {

    if (NULL === $othertrace) {
      $othertrace = debug_backtrace();
    }

    $trace_reverse = array_reverse($trace);
    $othertrace_reverse = array_reverse($othertrace);

    $trace_reverse_diff = self::reverseTraceDiff($trace_reverse, $othertrace_reverse);

    return array_reverse($trace_reverse_diff);
  }

  /**
   * @param array[] $trace
   *   Reverse trace.
   * @param array[] $othertrace
   *   Reverse trace to subtract from the first trace.
   *
   * @return array[]
   *   Part of $trace that is not shared in $othertrace.
   */
  private static function reverseTraceDiff(array $trace, array $othertrace): array {

    for ($i = 0;; ++$i) {
      if (!isset($trace[$i], $othertrace[$i])) {
        break;
      }
      // @todo Is this comparison reliable and sufficient?
      if ($trace[$i] !== $othertrace[$i]) {
        break;
      }
    }

    return \array_slice($trace, $i);
  }

  /**
   * @param array[] $backtrace
   *
   * @return array[]
   */
  public static function nicetrace(array $backtrace): array {

    $paths = [DRUPAL_ROOT => \strlen(DRUPAL_ROOT) + 1];
    return NicetraceUtil::backtraceGetNicetrace($backtrace, $paths);
  }

}
