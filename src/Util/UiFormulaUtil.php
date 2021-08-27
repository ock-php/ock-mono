<?php
declare(strict_types=1);

namespace Drupal\cu\Util;

use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\FormulaFactory\Formula_FormulaFactoryInterface;
use Donquixote\ObCK\Formula\ValueFactory\Formula_ValueFactoryInterface;

final class UiFormulaUtil extends UtilBase {

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *
   * @return string|null
   *
   * @throws \ReflectionException
   */
  public static function formulaGetDocComment(FormulaInterface $formula): ?string {

    if (NULL === $reflector = self::formulaGetReflector($formula)) {
      return NULL;
    }

    if ($reflector instanceof \ReflectionClass) {
      return $reflector->getDocComment();
    }

    if ($reflector instanceof \ReflectionFunctionAbstract) {
      return $reflector->getDocComment();
    }

    return NULL;
  }

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *
   * @return string|null
   *
   * @throws \ReflectionException
   */
  public static function formulaGetCodeSnippet(FormulaInterface $formula): ?string {

    if (NULL === $reflector = self::formulaGetReflector($formula)) {
      return NULL;
    }

    if (NULL === $snippet = self::reflectorGetSnippet(
      $reflector,
      TRUE,
      '' /* "\n    [..]\n  " */)
    ) {
      return NULL;
    }

    if (0
      || !$reflector instanceof \ReflectionMethod
      || ! $reflClass = $reflector->getDeclaringClass()
    ) {
      return $snippet;
    }

    $snippet = ''
      . "\n  [..]"
      . "\n"
      . "\n" . $snippet
      . "\n"
      . "\n  [..]"
      . "\n";

    $snippet = self::reflectorGetSnippet(
      $reflClass,
      FALSE,
      $snippet);

    return $snippet;
  }

  /**
   * @param \Reflector $reflector
   * @param bool $withDoc
   * @param string|null $replaceBody
   *
   * @return null|string
   */
  public static function reflectorGetSnippet(\Reflector $reflector, $withDoc = FALSE, $replaceBody = NULL): ?string {

    if (1
      && !$reflector instanceof \ReflectionClass
      && !$reflector instanceof \ReflectionFunctionAbstract
    ) {
      return NULL;
    }

    if (!$file = $reflector->getFileName()) {
      return NULL;
    }

    $start = $reflector->getStartLine() - 1;

    $snippet = self::fileGetLines(
      $file,
      $start,
      $reflector->getEndLine());

    if (FALSE === $pos = strpos($snippet, '{')) {
      return NULL;
    }

    if (NULL !== $replaceBody) {
      $snippet = substr($snippet, 0, $pos)
        . '{' . $replaceBody . '}';
    }

    if (1
      && $withDoc
      && NULL !== $doc = $reflector->getDocComment()
    ) {
      // Extract $doc in a way that includes leading line break and indent.
      $doc = self::fileGetLines(
        $file,
        $start - substr_count($doc, "\n") - 1,
        $start);

      $snippet = $doc . $snippet;
    }

    return $snippet;
  }

  /**
   * @param string $readableFile
   *   Passing a non-readable file violates the contract and results in runtime
   *   exception.
   * @param int $start
   * @param int $end
   *
   * @return string
   *
   * @todo Better exception handling for unreadable file?
   */
  public static function fileGetLines($readableFile, $start, $end): string {

    if (!is_readable($readableFile)) {
      throw new \RuntimeException("File '$readableFile' is not readable.");
    }

    $f = new \SplFileObject($readableFile);
    $f->seek($start);

    $php = '';
    for ($i = $start; !$f->eof() && $i < $end; $i++) {
      $php .= $f->current();
      $f->next();
    }

    return $php;
  }

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *
   * @return string|null
   *
   * @throws \ReflectionException
   */
  public static function formulaGetClass(FormulaInterface $formula): ?string {

    $reflector = self::formulaGetReflector($formula);

    if (!$reflector) {
      return NULL;
    }

    if ($reflector instanceof \ReflectionClass) {
      return $reflector->getName();
    }

    if ($reflector instanceof \ReflectionMethod) {
      return $reflector->getDeclaringClass()->getName();
    }

    return NULL;
  }

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *
   * @return \Reflector|null
   *
   * @throws \ReflectionException
   * @throws \Exception
   */
  public static function formulaGetReflector(FormulaInterface $formula): ?\Reflector {

    if ($formula instanceof Formula_ValueFactoryInterface) {
      $factory = $formula->getValueFactory();
    }
    elseif ($formula instanceof Formula_FormulaFactoryInterface) {
      $factory = $formula->getFormulaFactory();
    }
    else {
      return NULL;
    }

    $helper = new CodegenHelper();
    $php = $factory->argsPhpGetPhp(['...'], $helper);

    if (preg_match('@^new ((?:\\\\\w+)+)\(@', $php, $m)) {
      return new \ReflectionClass($m[1]);
    }

    if (preg_match('@^((?:\\\\\w+)+)::(\w+)\(@', $php, $m)) {
      return new \ReflectionMethod($m[1], $m[2]);
    }

    return NULL;
  }
}
