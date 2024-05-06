<?php

declare(strict_types = 1);

namespace Donquixote\CodegenTools;

use Donquixote\CodegenTools\Exception\CodegenException;

class Aliasifier {

  private const TYPE_CLASS = '';

  private const TYPE_FUNCTION = 'function ';

  private const TYPE_CONST = 'const ';

  /**
   * @var array<string, array<string, string>>
   *   Format: $[$type][$qcn] = $alias.
   */
  private array $aliasesByType = [
    self::TYPE_CLASS => [],
    self::TYPE_FUNCTION => [],
    self::TYPE_CONST => [],
  ];

  /**
   * @var array<string, array<string, string>>
   *   Format: $[$type][$alias] = $qcn.
   */
  private array $importsByType = [
    self::TYPE_CLASS => [],
    self::TYPE_FUNCTION => [],
    self::TYPE_CONST => [],
  ];

  /**
   * @param bool $appendLineBreak
   *   TRUE to append a line break if imports not empty.
   *
   * @return string
   */
  public function getImportsPhp(bool $appendLineBreak = true): string {
    $importsPhp = '';
    foreach ($this->importsByType as $importsForType) {
      sort($importsForType, SORT_FLAG_CASE | SORT_STRING);
      $importsPhp .= implode('', $importsForType);
    }
    if ($appendLineBreak && $importsPhp !== '') {
      $importsPhp .= "\n";
    }
    return $importsPhp;
  }

  /**
   * @param string $php
   *
   * @return static
   *
   * @throws \Donquixote\CodegenTools\Exception\CodegenException
   */
  public function aliasify(string &$php): static {
    $clone = clone $this;
    $clone->doAliasify($php);
    return $clone;
  }

  /**
   * @param string $php
   *
   * @throws \Donquixote\CodegenTools\Exception\CodegenException
   */
  private function doAliasify(string &$php): void {
    $tokens = \PhpToken::tokenize('<?php ' . $php);
    $type = NULL;
    $iLast = -1;
    /** @var array<int, string|null> $fqcnMap */
    $fqcnMap = [];
    for ($i = 0; $token = $tokens[$i] ?? null; ++$i) {
      switch ($token->id) {
        case \T_USE:
          // Note: Trait usage is not supported either.
          throw new CodegenException('Cannot aliasify code that already contains imports.');

        case \T_NAMESPACE:
          throw new CodegenException('Cannot aliasify code that is already in a namespace.');

        case \T_NAME_FULLY_QUALIFIED:
          $fqcnMap[$iLast = $i] = $type;
          $type = NULL;
          break;

        case \T_NEW:
        case \T_INSTANCEOF:
        case \T_EXTENDS:
        case \T_IMPLEMENTS:
        case \T_ATTRIBUTE:
          $type = '';
          $iLast = -1;
          break;

        case \T_WHITESPACE:
          // Don't change state.
          break;

        case \T_DOUBLE_COLON:
        case \T_VARIABLE:
          $type = NULL;
          $fqcnMap[$iLast] ??= self::TYPE_CLASS;
          $iLast = -1;
          break;

        case ord('(') . '_':
          $fqcnMap[$iLast] ??= self::TYPE_FUNCTION;
          break;

        default:
          $type = NULL;
          $iLast = -1;
      }
    }

    unset($fqcnMap[-1]);

    foreach ($fqcnMap as $i => $type) {
      if ($type === NULL) {
        $type = $this->determineFqcnType($tokens, $i);
      }
      $fqcn = (string) $tokens[$i];
      $qcn = substr($fqcn, 1);
      $pos = strrpos($qcn, '\\') ?: -1;
      if ($pos === -1) {
        // This is a top-level class name.
        continue;
      }
      $alias = $this->aliasesByType[$type][$qcn] ?? NULL;
      if ($alias === NULL) {
        $alias = substr($qcn, $pos + 1);
        $import = 'use ' . $type . $qcn;
        if (isset($this->importsByType[$type][$alias])) {
          for ($iAliasVariation = 0; isset($this->importsByType[$type][$alias]); ++$iAliasVariation) {
            $alias = substr($qcn, $pos + 1) . '_' . $iAliasVariation;
          }
          $import .= ' as ' . $alias;
        }
        $this->importsByType[$type][$alias] = $import . ";\n";
        $this->aliasesByType[$type][$qcn] = $alias;
      }
      $tokens[$i] = $alias;
    }

    array_shift($tokens);
    $php = implode('', $tokens);
  }

  private function determineFqcnType(array $tokens, int $i): string {
    try {
      $tokens[$i] = ' 5 ';
      /** @noinspection PhpExpressionResultUnusedInspection */
      token_get_all(implode('', $tokens), TOKEN_PARSE);
      return self::TYPE_CONST;
    }
    catch (\ParseError) {}
    try {
      $tokens[$i] = ' C::f ';
      /** @noinspection PhpExpressionResultUnusedInspection */
      token_get_all(implode('', $tokens), TOKEN_PARSE);
      return self::TYPE_FUNCTION;
    }
    catch (\ParseError) {}
    return self::TYPE_CLASS;
  }

}
