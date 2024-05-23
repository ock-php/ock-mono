<?php
declare(strict_types=1);

namespace Drupal\renderkit\Util;

final class ExceptionUtil extends UtilBase {

  /**
   * @param string $message
   * @param mixed[] $replacements
   *
   * @return string
   */
  public static function formatMessage(string $message, array $replacements): string {

    foreach ($replacements as $k => $v) {
      if ('?' === $k[0]) {
        $replacements[$k] = self::formatValue($v);
      }
    }

    return strtr($message, $replacements);
  }

  /**
   * Formats a value for use in an exception message.
   *
   * @param mixed $value
   *
   * @return mixed|string
   */
  public static function formatValue(mixed $value): mixed {

    switch ($type = \gettype($value)) {

      case 'object':
        return \get_class($value);

      case 'bool':
        return $value ? 'TRUE' : 'FALSE';
      case 'null':
        return 'NULL';

      case 'int':
      case 'float':
        return "($type)" . $value;

      case 'string':
        if (\strlen($value) > 30) {
          $value = ''
            . substr($value, 0, 15)
            . '[..]'
            . substr($value, -8);
        }
        return var_export($value, TRUE);

      case 'array':
        if ([] === $value) {
          return '[]';
        }
        return $type;

      default:
        return $type;
    }
  }

}
