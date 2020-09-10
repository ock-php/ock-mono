<?php
declare(strict_types=1);

namespace Donquixote\Cf\Util;

class HtmlUtil {

  /**
   * @param string|object $text
   *
   * @return string
   */
  public static function sanitize($text): string {
    return is_string($text)
      ? htmlspecialchars($text, ENT_QUOTES)
      : (string) $text;
  }

}
