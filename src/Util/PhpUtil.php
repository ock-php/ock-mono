<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Util;

use Donquixote\CallbackReflection\Exception\GeneratedCodeException;
use Donquixote\CallbackReflection\Util\CodegenUtil;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\OCUI\Exception\EvaluatorException_UnsupportedFormula;

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

    $php = CodegenUtil::autoIndent($php, '  ');
    $aliases = CodegenUtil::aliasify($php);

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
   * @param string $message
   *
   * @return string
   */
  public static function incompatibleConfiguration(string $message): string {

    return self::exception(
      EvaluatorException_IncompatibleConfiguration::class,
      $message);
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param string $expectedClass
   * @param mixed $foundValue
   *
   * @return string
   */
  public static function misbehavingSTA(FormulaInterface $formula, string $expectedClass, $foundValue): string {

    $formulaClass = \get_class($formula);

    $messagePhp = <<<EOT
''
. 'Attempted to create a ' . \\$expectedClass::class . ' object' . "\\n"
. 'from formula of class ' . \\$formulaClass::class . '.' . "\\n"
EOT;

    if (\is_object($foundValue)) {
      $valueClass = \get_class($foundValue);
      $messagePhp .= <<<EOT
. 'Found a ' . \\$valueClass::class . ' object instead.'
EOT;
    }
    else {
      $valueType = \gettype($foundValue);
      $messagePhp .= <<<EOT

. 'Found a $valueType value instead.'
EOT;
    }

    return self::exceptionWithMessagePhp(
      EvaluatorException_UnsupportedFormula::class,
      $messagePhp);
    
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param string $destinationClass
   *
   * @return string
   */
  public static function unableToSTA(FormulaInterface $formula, string $destinationClass): string {

    $formulaClass = \get_class($formula);

    $messagePhp = <<<EOT
''
. 'Unable to create a ' . \\$destinationClass::class . ' object' . "\\n"
. 'from formula of class ' . \\$formulaClass::class . '.'
EOT;

    return self::exceptionWithMessagePhp(
      EvaluatorException_UnsupportedFormula::class,
      $messagePhp);
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param string|null $message
   *
   * @return string
   */
  public static function unsupportedFormula(FormulaInterface $formula, $message = NULL): string {

    $formulaClass = \get_class($formula);

    $messagePhp = <<<EOT

'Unsupported formula of class ' . \\$formulaClass::class
EOT;

    if (NULL !== $message) {
      $messagePhp .= ''
        . "\n" . self::export($message);
    }

    return self::exceptionWithMessagePhp(
      EvaluatorException_UnsupportedFormula::class,
      $messagePhp);
  }

  /**
   * @param string $exceptionClass
   * @param string $message
   *
   * @return string
   */
  public static function exception(string $exceptionClass, string $message): string {

    $messagePhp = var_export($message, TRUE);

    return self::exceptionWithMessagePhp($exceptionClass, $messagePhp);
  }

  /**
   * @param string $exceptionClass
   * @param string $messagePhp
   *
   * @return string
   */
  private static function exceptionWithMessagePhp(string $exceptionClass, string $messagePhp): string {

    return <<<EOT
// @todo Fix the generated code manually.
call_user_func(
  static function () {
    throw new \\$exceptionClass($messagePhp);
  })
EOT;
  }

  /**
   * @param string $function
   * @param string[] $argsPhp
   *
   * @return string
   */
  public static function phpCallFunction(string $function, array $argsPhp): string {
    return $function . '(' . "\n" . self::phpCallArglist($argsPhp) . ')';
  }

  /**
   * @param string[] $argsPhp
   *
   * @return string
   */
  public static function phpCallArglist(array $argsPhp): string {
    return implode(",\n", $argsPhp);
  }

  /**
   * @param mixed $value
   *
   * @return string
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
   * @return mixed|string
   */
  public static function phpObject(object $object) {

    if ($object instanceof \stdClass) {
      return '(object)' . self::phpArray((array)$object);
    }

    if ($object instanceof \Closure) {
      return self::exception(
        GeneratedCodeException::class,
        'Cannot export closure.');
    }

    $valuess = ReflectionUtil::objectGetPropertyValuesDeep($object) + [0 => []];
    $values = $valuess[0];
    unset($valuess[0]);

    $argsPhp = [
      '\\' . \get_class($object) . '::class',
      self::phpValue($values),
    ];

    if ([] !== $valuess) {
      $argsPhp[] = self::phpValue($valuess);
    }

    return self::phpCallFunction(
      /* @see \Donquixote\OCUI\Util\ReflectionUtil::createInstance() */
      '\\' . ReflectionUtil::class . '::createInstance',
      $argsPhp);
  }

  /**
   * @param string $string
   *
   * @return string
   */
  public static function phpString(string $string): string {

    /*
    if (false !== strpos($string, '\\')) {
      if (preg_match(self::REGEX_NAMESPACE, $string)) {
        if (class_exists($string)) {
          return '\\' . $string . '::class';
        }
      }
    }
    */

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
    if (array_keys($valuesPhp) === range(0, count($valuesPhp) - 1)) {
      foreach ($valuesPhp as $k => $vPhp) {
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
   * @param mixed $value
   *
   * @return string
   */
  private static function export($value): string {
    return var_export($value, TRUE);
  }

}
