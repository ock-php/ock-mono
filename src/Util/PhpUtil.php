<?php

declare(strict_types=1);

namespace Donquixote\Ock\Util;

use Donquixote\Adaptism\Util\MessageUtil;

final class PhpUtil extends UtilBase {

  # public const REGEX_IDENTIFIER_CHAR_FIRST = /** @lang RegExp */ '[a-zA-Z_\x7f-\xff]';
  # public const REGEX_IDENTIFIER_CHAR_OTHER = /** @lang RegExp */ '[a-zA-Z0-9_\x7f-\xff]';
  # public const REGEX_IDENTIFIER_PATTERN = self::REGEX_IDENTIFIER_CHAR_FIRST . self::REGEX_IDENTIFIER_CHAR_OTHER . '*';
  # public const REGEX_IDENTIFIER = '/^' . self::REGEX_IDENTIFIER_PATTERN . '$/';
  # public const REGEX_NAMESPACE = '/^(?:' . self::REGEX_IDENTIFIER_PATTERN . '\\\\)*' . self::REGEX_IDENTIFIER_PATTERN . '$/';

  /**
   * @param string $php
   * @param string|null $namespace
   *
   * @return string
   */
  public static function formatAsFile(string $php, $namespace = NULL): string {

    $php = self::autoIndent($php, '  ');
    $aliases = self::aliasify($php);

    $aliases_php = '';
    foreach ($aliases as $class => $alias) {
      if (TRUE === $alias) {
        $aliases_php .= 'use ' . $class . ";\n";
      }
      else {
        $aliases_php .= 'use ' . $class . ' as ' . $alias . ";\n";
      }
    }

    if ('' !== $aliases_php) {
      $aliases_php = "\n" . $aliases_php;
    }

    $php = <<<EOT
$aliases_php

$php
EOT;

    if (NULL !== $namespace) {
      $php = <<<EOT
namespace $namespace;
$php
EOT;

    }

    return <<<EOT
<?php
$php
EOT;

  }

  /**
   * @param string $objectPhp
   * @param string $method
   * @param string[] $argsPhp
   *
   * @return string
   */
  public static function phpCallMethod(string $objectPhp, string $method, array $argsPhp): string {
    return self::phpCallFqn($objectPhp . '->' . $method, $argsPhp);
  }

  /**
   * @param callable $method
   *   Static method, as an array of two strings.
   * @param string[] $argsPhp
   *   Arguments as php expressions.
   *
   * @return string
   *   Php expression that calls the static method.
   */
  public static function phpCallStatic(callable $method, array $argsPhp): string {
    if (!is_array($method) || !is_string($method[0])) {
      throw new \InvalidArgumentException('Parameter $method must be a static method.');
    }
    return self::phpCallFqn('\\' . $method[0] . '::' . $method[1], $argsPhp);
  }

  /**
   * @param callable $callable
   *   Static method or function.
   * @param string[] $argsPhp
   *   Arguments as php expressions.
   *
   * @return string
   *   Php expression that calls the static method.
   */
  public static function phpCall(callable $callable, array $argsPhp): string {
    if (is_array($callable)) {
      if (!is_string($callable[0])) {
        throw new \InvalidArgumentException('Parameter must be a static method.');
      }
      return self::phpCallFqn('\\' . $callable[0] . '::' . $callable[1], $argsPhp);
    }
    if (is_string($callable)) {
      return self::phpCallFqn('\\' . $callable, $argsPhp);
    }
    throw new \InvalidArgumentException('Expected a static method or a function name.');
  }

  /**
   * @param class-string $class
   * @param string[] $argsPhp
   *
   * @return string
   */
  public static function phpNewClass(string $class, array $argsPhp): string {
    return self::phpCallFqn('new \\' . $class, $argsPhp);
  }

  /**
   * @param callable-string $function
   * @param string[] $argsPhp
   *
   * @return string
   */
  public static function phpCallFunction(string $function, array $argsPhp): string {
    return self::phpCallFqn('\\' . $function, $argsPhp);
  }

  /**
   * @param string $fqn
   * @param list<string> $argsPhp
   *
   * @return string
   */
  public static function phpCallFqn(string $fqn, array $argsPhp): string {
    if ($argsPhp === []) {
      return $fqn . '()';
    }
    $php = $fqn . self::phpCallArglist($argsPhp);
    if (strlen($php) > 80 || str_contains($php, "\n")) {
      // Code is too long for a single line, or already contains line breaks.
      // Insert line breaks between arguments.
      // This is a temporary solution to make tests pass.
      // @todo Insert line breaks as a post-processing step instead.
      $php = $fqn . self::phpCallArglist($argsPhp, TRUE);
    }
    return $php;
  }

  /**
   * @param list<string> $argsPhp
   * @param bool $break
   *
   * @return string
   */
  public static function phpCallArglist(array $argsPhp, bool $break = FALSE): string {
    if ($argsPhp === []) {
      return '()';
    }
    if (!$break) {
      return '(' . implode(', ', $argsPhp) . ')';
    }
    return "(\n"
      . implode(",\n", $argsPhp)
      . ",\n)";
  }

  /**
   * Exports a simple value as a php value expression.
   *
   * Throws an "unchecked" exception on failure.
   *
   * @param mixed $value
   *   Simple value that is known to be safe for export.
   *
   * @return string
   *   PHP value expression.
   */
  public static function phpValueSimple($value): string {
    try {
      return self::phpValue($value);
    }
    catch (\Exception $e) {
      // Convert to an "unchecked" exception.
      throw new \InvalidArgumentException(
        sprintf(
          'Value %s was not as simple as expected.',
          MessageUtil::formatValue($value)),
        0,
        $e);
    }
  }

  /**
   * Attempts to exports a value as a PHP value expression.
   *
   * Throws a "checked" exception on failure.
   *
   * @param mixed $value
   *   Value that is hoped to be safe for export.
   *
   * @return string
   *   PHP value expression.
   *
   * @throws \Exception
   *   Value cannot be exported.
   */
  public static function phpValue($value): string {

    if (\is_array($value)) {

      $valuesPhp = [];
      foreach ($value as $k => $v) {
        $valuesPhp[$k] = self::phpValue($v);
      }

      return self::phpArray($valuesPhp);
    }

    if (\is_object($value)) {
      return self::phpObject($value);
    }

    if (\is_string($value)) {
      return self::phpString($value);
    }

    return var_export($value, TRUE);
  }

  /**
   * @param object $object
   *
   * @return string
   *
   * @throws \Exception
   *   Object cannot be exported.
   */
  public static function phpObject(object $object): string {
    if ($object instanceof \stdClass) {
      return '(object) ' . self::phpArray((array) $object);
    }

    if ($object instanceof \Closure) {
      throw new \Exception('Cannot export closure.');
    }

    // Attempt to serialize.
    // If the class does not support it, an exception will be thrown.
    return \sprintf(
      '\\unserialize(%s)',
      self::phpString(\serialize($object)),
    );
  }

  /**
   * @param string $string
   *
   * @return string
   */
  public static function phpString(string $string): string {
    return var_export($string, TRUE);
  }

  /**
   * @param string[] $valuesPhp
   *
   * @return string
   */
  public static function phpArray(array $valuesPhp): string {

    if ([] === $valuesPhp) {
      return '[]';
    }

    $php = '';
    if (array_is_list($valuesPhp)) {
      foreach ($valuesPhp as $vPhp) {
        $php .= "\n  $vPhp,";
      }
    }
    else {
      foreach ($valuesPhp as $k => $vPhp) {
        $kPhp = var_export($k, TRUE);
        $php .= "\n  $kPhp => $vPhp,";
      }
    }

    return "[$php\n]";
  }

  /**
   * @param string $php
   * @param string $indentation
   *
   * @return string
   */
  public static function indent(string $php, string $indentation): string {
    $tokens = token_get_all('<?php' . "\n" . $php);
    array_shift($tokens);
    $out = '';
    foreach ($tokens as $token) {
      if (\is_string($token)) {
        $out .= $token;
      }
      elseif (!\in_array($token[0], [\T_WHITESPACE, \T_DOC_COMMENT, \T_COMMENT])) {
        $out .= $token[1];
      }
      else {
        $out .= str_replace("\n", "\n" . $indentation, $token[1]);
      }
    }
    return $out;
  }

  /**
   * Replaces long class names with aliases.
   *
   * @param string $php
   *   PHP code without the leading <?php.
   *
   * @return mixed[]
   *   Format: $[$class] = $alias|true
   */
  public static function aliasify(string &$php): array {
    $php_full = '<?php' . "\n" . $php . "\n";
    $tokens = token_get_all($php_full);
    $tokens[] = '#';

    /**
     * @var int[] = $candidates
     *   Format: $[] = $i0.
     */
    $i0s = [];
    $i0 = NULL;
    $add_next = FALSE;
    foreach ($tokens as $i => $token) {
      switch ($token[0]) {
        case T_STRING:
        case T_NS_SEPARATOR:
        case T_NAME_FULLY_QUALIFIED:
        case T_NAME_QUALIFIED:
          if ($i0 === NULL) {
            // A new qcn or fqcn starts here.
            if ($add_next) {
              $i0s[] = $i;
              $add_next = FALSE;
            }
            $i0 = $i;
          }
          break;

        case T_NEW:
        case T_INSTANCEOF:
        case ':':
        case '?':
        case \T_ATTRIBUTE:
          // Next qcn or fqcn must be a class name.
          $add_next = TRUE;
          break;

        case T_WHITESPACE:
          // Don't change state.
          break;

        case T_DOUBLE_COLON:
        case T_VARIABLE:
        case '&':
          if ($i0 !== NULL) {
            $i0s[] = $i0;
            $i0 = NULL;
          }
          $add_next = FALSE;
          break;

        default:
          $i0 = NULL;
          $add_next = FALSE;
      }
    }

    /**
     * @var array<string,list<array{int,int}>> $map
     *   Format: $[$name][] = [$i0, $i1]
     */
    $map = [];
    foreach ($i0s as $i0) {
      $tk0 = $tokens[$i0];
      switch ($tk0[0]) {
        case T_NS_SEPARATOR:
          $n0 = $i0 + 1;
          $class = '';
          break;

        case T_NAME_FULLY_QUALIFIED:
          $n0 = NULL;
          $class = substr($tk0[1], 1);
          break;

        case T_STRING:
          if ($tokens[$i0 + 1][0] !== T_NS_SEPARATOR) {
            // This class is not in a namespace.
            $class = $tk0[1];
            if (self::typeIsBuiltin($class)) {
              continue 2;
            }
            $n0 = NULL;
          }
          else {
            $n0 = $i0;
            $class = '';
          }
          break;

        case T_NAME_QUALIFIED:
          $n0 = NULL;
          $class = $tk0[1];
          break;

        default:
          continue 2;
      }

      if ($n0 === NULL) {
        $map[$class][] = [$i0, $i0];
      }
      else {
        for ($j = $n0; TRUE; $j += 2) {
          if ($tokens[$j][0] !== T_STRING) {
            // Malformed class name.
            continue 2;
          }
          $class .= $tokens[$j][1];
          if ($tokens[$j + 1][0] !== T_NS_SEPARATOR) {
            $map[$class][] = [$i0, $j];
            break;
          }
          $class .= '\\';
        }
      }
    }

    /**
     * @var array<string,array<string,list<array{int,int}>>> $mm
     *   Format: $[$shortname][$class][] = [$i0, $i1]
     * @var array<string,list<array{int,int}>> $abs
     *   Format: $['\\' . $class][] = [$0, $i1]
     */
    $mm = [];
    $abs = [];
    foreach ($map as $name => $indices_by_position) {
      if (FALSE !== $pos0 = strrpos($name, '\\')) {
        // The class is in a non-root namespace.
        $shortname = substr($name, $pos0 + 1);
        $mm[$shortname][$name] = $indices_by_position;
      }
      else {
        // The class is in the root namespace.
        $abs['\\' . $name] = $indices_by_position;
      }
    }

    $alias_map = [];
    foreach ($mm as $alias_base => $classes) {
      $alias = $alias_base;
      $i_alias_variation = 0;
      foreach ($classes as $name => $indices_by_position) {
        $alias_map[$name] = (0 === $i_alias_variation) ? TRUE : $alias;
        foreach ($indices_by_position as list($i0, $i1)) {
          $tokens[$i1] = $alias;
          /**
           * @var int $i0
           */
          for ($i = $i0; $i < $i1; ++$i) {
            if (T_WHITESPACE !== $tokens[$i][0]) {
              $tokens[$i] = '';
            }
          }
        }
        ++$i_alias_variation;
        $alias = $alias_base . '_' . $i_alias_variation;
      }
    }

    ksort($alias_map, SORT_STRING | SORT_FLAG_CASE);

    foreach ($abs as $qcn_or_fqcn => $indices_by_position) {
      foreach ($indices_by_position as list($i0, $i1)) {
        $tokens[$i1] = $qcn_or_fqcn;
        for ($i = $i0; $i < $i1; ++$i) {
          if (T_WHITESPACE !== $tokens[$i][0]) {
            $tokens[$i] = '';
          }
        }
      }
    }

    array_shift($tokens);
    array_pop($tokens);
    array_pop($tokens);

    $php = '';
    foreach ($tokens as $token) {
      if (\is_string($token)) {
        $php .= $token;
      }
      else {
        $php .= $token[1];
      }
    }

    return $alias_map;
  }

  /**
   * Checks if a given type name is built-in.
   *
   * @param string $type
   *   The type name.
   *
   * @return bool
   *   TRUE for e.g. 'string', 'int' etc, FALSE for class names.
   */
  public static function typeIsBuiltin(string $type): bool {
    static $types_map = [];
    return $types_map[$type]
      ?? ($types_map[$type] = self::determineIfTypeIsBuiltin($type));
  }

  /**
   * Checks if a given type name is built-in.
   *
   * @param string $type
   *   The type name.
   *
   * @return bool
   *   TRUE for e.g. 'string', 'int' etc, FALSE for class names.
   */
  private static function determineIfTypeIsBuiltin(string $type): bool {
    if (!preg_match('@^[a-zA-Z]+$@', $type)) {
      // The type name is not safe to use in eval().
      return FALSE;
    }
    $eval = "return static function (): $type {};";
    $f = eval($eval);
    try {
      $rf = new \ReflectionFunction($f);
    }
    catch (\ReflectionException) {
      return FALSE;
    }
    $rt = $rf->getReturnType();
    if (!$rt) {
      return FALSE;
    }
    return $rt->isBuiltin();
  }

  /**
   * Formats aliases as imports.
   *
   * @param mixed[] $aliases
   *   Format: $[$class] = $alias|true
   * @param string $prepend
   *   String to prepend if the list is not empty.
   *   This is useful to add line breaks.
   *
   * @return string
   *   Formatted PHP.
   */
  public static function formatAliases(array $aliases, string $prepend = "\n"): string {
    if (!$aliases) {
      return '';
    }
    ksort($aliases, SORT_STRING | SORT_FLAG_CASE);

    $aliases_php = '';
    foreach ($aliases as $class => $alias) {
      if (TRUE === $alias) {
        $aliases_php .= 'use ' . $class . ";\n";
      }
      else {
        $aliases_php .= 'use ' . $class . ' as ' . $alias . ";\n";
      }
    }

    return $prepend . $aliases_php;
  }

  /**
   * @param string $php
   * @param string $indent_level
   * @param string $indent_base
   *
   * @return string
   */
  public static function autoIndent($php, $indent_level, $indent_base = '') {
    $tokens = token_get_all('<?php' . "\n" . $php);
    $tokens[] = [T_WHITESPACE, "\n"];
    $tokens[] = '#';
    $tokens = self::prepareTokens($tokens);

    $i = 1;
    $out = [''];
    self::doAutoIndent($out, $tokens, $i, $indent_base, $indent_level);

    array_pop($out);

    return implode('', $out);
  }

  /**
   * @param array $tokens_original
   *
   * @return array
   */
  private static function prepareTokens(array $tokens_original) {

    $tokens_prepared = [];
    for ($i = 0; TRUE; ++$i) {
      $token = $tokens_original[$i];
      if (T_COMMENT === $token[0]) {
        if (str_ends_with($token[1], "\n")) {
          $tokens_prepared[] = [T_COMMENT, substr($token[1], 0, -1)];
          if (T_WHITESPACE === $tokens_original[$i + 1][0]) {
            $tokens_prepared[] = [T_WHITESPACE, "\n" . $tokens_original[$i + 1][1]];
            ++$i;
          }
          else {
            $tokens_prepared[] = [T_WHITESPACE, "\n"];
          }
          continue;
        }
      }

      $tokens_prepared[] = $token;

      if ('#' === $token) {
        break;
      }
    }

    return $tokens_prepared;
  }

  /**
   * @param string[] $out
   * @param list<string|array{int, string, int}> $tokens
   * @param int $i
   * @param string $indent_base
   * @param string $indent_level
   */
  private static function doAutoIndent(array &$out, array $tokens, int &$i, string $indent_base, string $indent_level): void {

    $indent_deeper = $indent_base . $indent_level;

    while (TRUE) {
      $token = $tokens[$i];
      if (\is_string($token)) {
        switch ($token) {
          case '{':
          case '(':
          case '[':
            $out[] = $token;
            ++$i;
            self::doAutoIndent($out, $tokens, $i, $indent_deeper, $indent_level);
            if ('#' === $tokens[$i]) {
              return;
            }
            if (T_WHITESPACE === $tokens[$i - 1][0]) {
              $out[$i - 1] = str_replace($indent_deeper, $indent_base, $out[$i - 1]);
            }
            break;

          case '}':
          case ')':
          case ']':
            $out[] = $token;
            return;

          case '#':
            return;

          default:
            $out[] = $token;
            break;
        }
      }
      else {
        switch ($token[0]) {
          case T_WHITESPACE:
            $n_linebreaks = substr_count($token[1], "\n");
            if (0 === $n_linebreaks) {
              $out[] = $token[1];
              ++$i;
              continue 2;
            }
            $out[] = str_repeat("\n", $n_linebreaks) . $indent_base;
            break;

          case T_COMMENT:
          case T_DOC_COMMENT:
            # $out[] = $token[1];
            $out[] = preg_replace(
            /** @lang RegExp */
              "@ *\\n *\\*@",
              "\n" . $indent_base . ' *',
              $token[1]);
            break;

          default:
            $out[] = $token[1];
            break;
        }
      }

      ++$i;
    }
  }

  /**
   * Prepends a 'return ' to a PHP value expression.
   *
   * @param string $expression
   *  PHP value expression.
   *
   * @return string
   *   PHP statement with added 'return ' statement.
   */
  public static function buildReturnStatement(string $expression): string {
    $php_full = '<?php ' . $expression;
    $tokens = token_get_all($php_full);
    # array_shift($tokens);
    $statement_full = '';
    $return_added = FALSE;
    foreach ($tokens as $token) {
      if (is_string($token)) {
        $statement_full .= $token;
        continue;
      }
      if (!$return_added) {
        switch ($token[0]) {
          case T_WHITESPACE:
          case T_COMMENT:
          case T_DOC_COMMENT:
          case T_OPEN_TAG:
            break;

          default:
            $statement_full .= 'return ';
            $return_added = TRUE;
        }
      }
      $statement_full .= $token[1];
    }
    return substr($statement_full, 6) . ';';
  }

}
