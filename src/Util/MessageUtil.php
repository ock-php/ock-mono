<?php

declare(strict_types=1);

namespace Donquixote\Ock\Util;

class MessageUtil extends UtilBase {

  /**
   * @param mixed $value
   *
   * @return string
   */
  public static function formatValue($value): string {
    switch ($type = gettype($value)) {
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

}
