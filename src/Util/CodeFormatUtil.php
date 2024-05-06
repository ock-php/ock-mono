<?php

declare(strict_types = 1);

namespace Donquixote\CodegenTools\Util;

use Donquixote\CodegenTools\Aliasifier;
use Donquixote\CodegenTools\LineBreaker;

class CodeFormatUtil {

  /**
   * Formats the code as a php file with open tag and strict types declaration.
   *
   * @param string $php
   * @param string|null $namespace
   *
   * @return string
   *
   * @throws \Donquixote\CodegenTools\Exception\CodegenException
   */
  public static function formatAsFile(string $php, string $namespace = NULL): string {
    $filePhp = "<?php\n\ndeclare(strict_types=1);\n\n"
      . self::formatAsSnippet($php, $namespace);
    if (!str_ends_with($filePhp, "\n")) {
      // Files should always end with a line break.
      $filePhp .= "\n";
    }
    return $filePhp;
  }

  /**
   * Formats the code as a snippet without php open tag.
   *
   * @param string $phpExpression
   *
   * @return string
   *
   * @throws \Donquixote\CodegenTools\Exception\CodegenException
   */
  public static function formatExpressionAsSnippet(string $phpExpression): string {
    $statement = 'return ' . $phpExpression . ';';
    return self::formatAsSnippet($statement);
  }

  /**
   * Formats the code as a snippet without php open tag.
   *
   * This can be used e.g. to embed in a yml file.
   *
   * @param string $php
   * @param string|null $namespace
   *
   * @return string
   *
   * @throws \Donquixote\CodegenTools\Exception\CodegenException
   */
  public static function formatAsSnippet(string $php, string $namespace = NULL): string {
    $php = (new Aliasifier())->aliasify($php)->getImportsPhp() . $php;

    if (NULL !== $namespace) {
      $php = 'namespace ' . $namespace . ";\n\n" . $php;
    }

    $php = (new LineBreaker())->breakLongLines($php);

    return $php;
  }

}
