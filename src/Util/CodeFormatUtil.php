<?php

declare(strict_types = 1);

namespace Donquixote\CodegenTools\Util;

use Donquixote\CodegenTools\CodeFormatter;

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
    return CodeFormatter::create()->formatAsFile($php, $namespace);
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
    return CodeFormatter::create()->formatExpressionAsSnippet($phpExpression);
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
    return CodeFormatter::create()->formatAsSnippet($php, $namespace);
  }

}
