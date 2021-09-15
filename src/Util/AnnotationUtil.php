<?php

declare(strict_types=1);

namespace Donquixote\Ock\Util;

class AnnotationUtil {

  /**
   * @param string $docComment
   * @param string $name
   *
   * @return bool
   */
  public static function docCommentHasArglessAnnotationName(string $docComment, string $name): bool {

    if (FALSE === strpos($docComment, '@' . $name)) {
      return FALSE;
    }

    $pattern = ''
      . '~(' . '^/\*\*\h+' . '|' . '\v\h*(\*\h+|)' . ')@'
      . preg_quote($name, '~')
      . '(\(\)|)' . '(\h*\v|\h*\*/$)~';

    if (!preg_match($pattern, $docComment)) {
      return FALSE;
    }

    return TRUE;
  }

}
