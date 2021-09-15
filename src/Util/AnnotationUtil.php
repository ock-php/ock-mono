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

    $pattern = '~'
      // Start the doc with '/**', or start the line with  '*' or ''.
      . '(' . '^/\*\*\h+' . '|' . '\v\h*(\*\h+|)' . ')'
      // Annotation name starting with '@'.
      . preg_quote('@' . $name, '~')
      // Empty '()' or empty string.
      . '(\(\)|)'
      // End the line, or end the doc with '*/'.
      . '(\h*\v|\h*\*/$)'
      . '~';

    if (!preg_match($pattern, $docComment)) {
      return FALSE;
    }

    return TRUE;
  }

}
