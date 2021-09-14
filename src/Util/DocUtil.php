<?php

declare(strict_types=1);

namespace Donquixote\Ock\Util;

final class DocUtil extends UtilBase {

  /**
   * @param string $docComment
   * @param string $selfClassName
   *
   * @return string[]
   */
  public static function docGetReturnTypeClassNames(string $docComment, string $selfClassName): array {

    if (!preg_match('~(?:^/\*\*\ +|\v\h*\* )@return\h+(\S+)~', $docComment, $m)) {
      return [];
    }

    $names = [];
    foreach (explode('|', $m[1]) as $alias) {

      if ('' === $alias) {
        continue;
      }

      if ('\\' === $alias[0] && preg_match('~(\\\\[a-zA-Z_][a-zA-Z_0-9]*)+~', $alias)) {
        $names[] = substr($alias, 1);
      }
      elseif ('self' === $alias || 'static' === $alias) {
        $names[] = $selfClassName;
      }
    }

    return $names;
  }

}
