<?php

declare(strict_types=1);

namespace Donquixote\Ock\Util;

class MessageUtil extends UtilBase {

  /**
   * @param string $expected
   *   Message part to describe expected value.
   * @param mixed $value
   *   Actual value found.
   *
   * @return string
   *   Formatted message.
   */
  public static function expectedButFound(string $expected, $value): string {
    return sprintf(
      'Expected %s, but found %s.',
      $expected,
      self::formatValue($value));
  }

  /**
   * @param mixed $value
   *
   * @return string
   */
  public static function formatValue($value): string {
    switch (gettype($value)) {
      case 'object':
        return get_class($value) . ' object';

      case 'array':
        return $value ? 'array(..)' : 'array()';

      case 'resource':
        return 'resource';

      case 'integer':
        return '(int)' . $value;

      default:
        return var_export($value, TRUE);
    }
  }

  /**
   * @param \ReflectionFunctionAbstract $function
   *
   * @return string
   */
  public static function formatReflectionFunction(\ReflectionFunctionAbstract $function): string {
    $name = $function->getName() . '()';
    if ($function instanceof \ReflectionMethod) {
      $class = $function->getDeclaringClass();
      if ($function->isStatic()) {
        $name = $class->getName() . '::' . $name;
      }
      else {
        $name = $class->getName() . '->' . $name;
      }
    }
    return $name;
  }

}
