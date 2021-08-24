<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Util;

class DecoUtil extends UtilBase {

  const PLACEHOLDER = '\\' . self::class . '::placeholder()';

  /**
   * @throws \Exception
   */
  public static function placeholder() {
    // @todo Better exception type and message.
    throw new \Exception();
  }

}
