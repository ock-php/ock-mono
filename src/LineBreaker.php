<?php

declare(strict_types = 1);

namespace Donquixote\CodegenTools;

class LineBreaker {

  private const MODE_FILE = 'file';

  private const MODE_ENCLOSED_VALUE = 'value';

  private const MODE_LIST_INLINE = 'list_inline';

  private const MODE_LIST_MULTILINE = 'list_multiline';

  private const MODE_OTHER_INLINE = 'curly_inline';

  private const MODE_OTHER_MULTILINE = 'curly_multiline';

  private string $indentLevel = '  ';

  private int $limit = 80;

  private int $shortLimit = 40;

  /**
   * Operators that will be considered for line breaks, ordered by precedence.
   *
   * See https://www.php.net/manual/en/language.operators.precedence.php
   *
   * @var array
   */
  const OPERATORS = [
    [],
    ['*', '%'],
    ['+', '-'],
    ['<<', '>>'],
    ['.'],
    ['<', '<=', '>', '>='],
    ['==', '!=', '===', '!==', '<>', '<=>'],
    ['&'],
    ['^'],
    ['|'],
    ['&&'],
    ['||'],
    ['??'],
    ['?', ':'],
    ['=', '*=', '-=', '*=', '**=', '/=', '.=', '%=', '&=', '|=', '^=', '<<=', '>>=', '??='],
    ['and'],
    ['xor'],
    ['or'],
  ];

  private array $operatorMap = [];

  public function withMaxLineLength(int $limit): static {
    $clone = clone $this;
    $clone->limit = $limit;
    return $clone;
  }

  /**
   * @param string $php
   *
   * @return string
   */
  public function breakLongLines(string $php): string {
    if ($this->operatorMap === []) {
      $this->operatorMap = $this->buildOperatorMap();
    }
    $orig = $php;
    $tokens = $this->tokenizeWithTrailingCommas($php);
    // Append two terminating tokens to allow lookaheads.
    $tokens[] = '#';
    $tokens[] = '#';
    $i = 0;
    $phpFormatted = $this->formatSection($tokens, $i, '', 0);
    if ($tokens[$i] !== '#') {
      // This was not the end.
      // The code is broken, but it is not the job of the prettifier to fix it.
      for (; $tokens[$i] !== '#'; ++$i) {
        $token = $tokens[$i];
        if (is_string($token)) {
          $phpFormatted .= $token;
        }
        else {
          $phpFormatted .= $token[1];
        }
      }
    }
    return $phpFormatted;
  }

  private function tokenizeWithTrailingCommas(string $php): array {
    // Check if the original code is valid.
    try {
      $tokens = \PhpToken::tokenize('<?php ' . $php, TOKEN_PARSE);
    }
    catch (\ParseError) {
      // Original code is not valid.
      // Give up, return the original php.
      return token_get_all('<?php ' . $php);
    }
    $phpNew = '';
    $commaPositions = [];
    $linesOffset = 0;
    foreach ($tokens as $i => $token) {
      switch ($token->getTokenName()) {
        case ')':
        case ']':
          for ($j = $i - 1; $tokens[$j]->isIgnorable(); --$j) {}
          if (in_array($tokens[$j]->getTokenName(), [',', ';', '[', '('])) {
            // Don't add a trailing comma here.
            break;
          }
          // Add a trailing comma. See if it blows up later.
          $commaPositions[$token->line + $linesOffset] = strlen($phpNew);
          // Add a line break so that we can later find it by line number.
          $phpNew .= ",\n";
          ++ $linesOffset;
          break;
      }
      $phpNew .= $token;
    }
    $posshift = 0;
    $lineshift = 0;
    $versions = ['<?php ' . $php, $phpNew];
    for (;;) {
      try {
        /** @noinspection PhpExpressionResultUnusedInspection */
        token_get_all($phpNew, TOKEN_PARSE);
        // Remove remaining line breaks.
        foreach ($commaPositions as $pos) {
          // Remove just the line break, but keep the comma.
          $phpNew = substr_replace($phpNew, '', $pos - $posshift + 1, 1);
          $versions[] = $phpNew;
          $posshift += 1;
        }
        return token_get_all($phpNew, TOKEN_PARSE);
      }
      catch (\ParseError $e) {
        $eLine = $e->getLine() + $lineshift;
        foreach ($commaPositions as $line => $pos) {
          unset($commaPositions[$line]);
          if ($line === $eLine) {
            // Remove the comma and line break.
            $phpNew = substr_replace($phpNew, '', $pos - $posshift, 2);
            $versions[] = $phpNew;
            $posshift += 2;
            ++$lineshift;
            break;
          }
          if ($line >= $eLine) {
            // Give up, return the original php.
            return token_get_all('<?php ' . $php);
          }
          // Remove just the line break, but keep the comma.
          $phpNew = substr_replace($phpNew, '', $pos - $posshift + 1, 1);
          $versions[] = $phpNew;
          ++$lineshift;
          ++$posshift;
        }
      }
    }
  }

  private function buildOperatorMap(): array {
    $map = [];
    $php = '<?php ';
    foreach (self::OPERATORS as $precedence => $operators) {
      foreach ($operators as $operator) {
        $map[$operator] = $precedence;
        $php .= $operator . ' ';
      }
      $php .= ", ";
    }
    $tokens = token_get_all($php);
    $precedence = 0;
    $n = count($tokens);
    for ($i = 1; $i < $n; $i += 2) {
      $token = $tokens[$i];
      if ($token === ',') {
        ++$precedence;
      }
      else {
        $map[$token[0]] = $precedence;
      }
    }
    return $map;
  }

  /**
   * Formats a section starting with '[' or '('.
   *
   * @param array $tokens
   * @param int $i
   * @param string $indent
   * @param int $position
   *
   * @return string
   */
  private function formatSection(
    array $tokens,
    int &$i,
    string $indent,
    int $position,
  ): string {
    /** @readonly $iStart */
    $iStart = $i;
    $indentBase = $indent;
    $mode = match ($tokens[$iStart]) {
      '{' => self::MODE_OTHER_INLINE,
      '(', '[' => self::MODE_ENCLOSED_VALUE,
      default => self::MODE_FILE,
    };
    // Multiple attempts to format the enclosed snippet.
    for ($iSnippetAttempt = 0;; ++$iSnippetAttempt) {
      if ($iSnippetAttempt >= 5) {
        throw new \RuntimeException("Infinite loop in snippet.");
      }
      $php = '';
      $i = $iStart;
      // Iterate over the segments.
      for ($iSegment = 0;; ++$iSegment) {
        assert(in_array($tokens[$i][0], [',', ';', '(', '[', '{', T_OPEN_TAG]));
        $iCommaOrStart = $i;
        $phpAtSegmentStart = $php;
        $phpIfSegmentEmpty = $php;
        $breakOnOperator = 99;
        // Multiple attempts to format the segment.
        for ($iSegmentAttempt = 0;; ++$iSegmentAttempt) {
          if ($iSegmentAttempt >= 5) {
            throw new \RuntimeException("Infinite loop in segment $iSegment.");
          }
          $indent = $indentSection = match ($mode) {
            self::MODE_OTHER_MULTILINE,
            self::MODE_LIST_MULTILINE => $indentBase . $this->indentLevel,
            default => $indentBase,
          };
          $php = $phpAtSegmentStart;
          $i = $iCommaOrStart;
          if ($iSegment > 0) {
            $php .= $tokens[$iCommaOrStart];
          }
          ++$i;
          if ($tokens[$i][0] === T_WHITESPACE) {
            if ($mode === self::MODE_OTHER_INLINE) {
              if (str_contains($tokens[$i][1], "\n")) {
                // Try again.
                $mode = self::MODE_OTHER_MULTILINE;
                continue;
              }
            }
            elseif (in_array($mode, [
              self::MODE_OTHER_MULTILINE,
              self::MODE_FILE,
            ])) {
              if ($nBr = substr_count($tokens[$i][1], "\n")) {
                $php .= str_repeat("\n", $nBr);
                $phpIfSegmentEmpty = $php . $indentBase;
                $php .= $indentSection;
              }
            }
            ++$i;
          }
          $iSegmentStart = $i;
          if ($mode === self::MODE_LIST_MULTILINE) {
            $php .= "\n" . $indent;
          }
          elseif ($mode === self::MODE_LIST_INLINE && $iSegment > 0) {
            $php .= ' ';
          }
          $breakOnOperatorCandidate = 0;
          $hasOperatorBreaks = false;
          $hasNestedExpression = false;
          // Iterate over the tokens in the part.
          for (;; ++$i) {
            $token = $tokens[$i];
            switch ($token[0]) {
              case '(':
              case '[':
              case '{':
                $php .= $token;
                // Nested snippet.
                $nested = $this->formatSection(
                  $tokens,
                  $i,
                  $indent,
                  $this->calcBrPos($php, $position),
                );
                if (str_contains($nested, "\n")) {
                  if (!str_starts_with($nested, "\n")) {
                    $hasNestedExpression = true;
                  }
                  if ($mode === self::MODE_LIST_INLINE) {
                    if (str_contains($php, "\n")) {
                      // Try again, as multiline.
                      $mode = self::MODE_LIST_MULTILINE;
                      continue 5;
                    }
                  }
                }
                $php .= $nested;
                if ($tokens[$i] === '#') {
                  return $php;
                }
                $php .= $tokens[$i];
                break;

              case ';':
                // This could be part of a `for (*;*;*)`.
                // Don't insert linebreaks.
                if ($mode === self::MODE_LIST_INLINE) {
                  // Start over.
                  $mode = self::MODE_OTHER_INLINE;
                  continue 5;
                }
                elseif ($mode === self::MODE_LIST_MULTILINE) {
                  $mode = self::MODE_OTHER_MULTILINE;
                  continue 5;
                }
                elseif ($mode === self::MODE_ENCLOSED_VALUE) {
                  $mode = self::MODE_OTHER_INLINE;
                  continue 5;
                }
                break 2;

              case ',':
                // End of segment.
                if ($mode === self::MODE_ENCLOSED_VALUE) {
                  $mode = self::MODE_LIST_INLINE;
                }
                if ($mode === self::MODE_LIST_INLINE && $hasOperatorBreaks) {
                  // Try again.
                  $mode = self::MODE_LIST_MULTILINE;
                  continue 5;
                }
                break 2;

              case ')':
              case ']':
              case '}':
                // Enclosed part ends here.
                // The bracket type could be wrong, but we don't care about
                // verification.
                if ($i === $iSegmentStart) {
                  // The segment is empty. Remove the comma and indent.
                  $php = $phpIfSegmentEmpty;
                  if ($iSegment === 0) {
                    // The complete enclosed snippet is empty.
                    // It is either an empty argument list, or an empty array.
                    return $php;
                  }
                  // Skip end-of-segment operations.
                  break 4;
                }
                // Do end-of-segment operations before end-of-snippet.
                break 2;

              case '#':
                // End of string.
                // Do end-of-segment operations before end-of-snippet.
                break 2;

              case T_WHITESPACE:
                if ($mode !== 'x'
                  && (NULL !== $precedence = $this->operatorMap[$tokens[$i + 1][0]] ?? null)
                  && $tokens[$i + 2][0] === T_WHITESPACE
                ) {
                  // An operator surrounded by whitespace on both sides is
                  // assumed to be binary. It is up to the code generator to
                  // make sure this is the case.
                  if ($breakOnOperator <= $precedence) {
                    $indent = $indentSection . $this->indentLevel;
                    $php .= "\n" . $indent;
                    $hasOperatorBreaks = true;
                  }
                  else {
                    $php .= ' ';
                    if ($breakOnOperatorCandidate < $precedence) {
                      // Set the next best operator precedence to break on.
                      $breakOnOperatorCandidate = $precedence;
                    }
                  }
                  continue 2;
                }
                if (in_array($mode, [
                  self::MODE_OTHER_MULTILINE,
                  self::MODE_FILE,
                ])) {
                  if ($nBr = substr_count($token[1], "\n")) {
                    $php .= str_repeat("\n", $nBr) . $indent;
                  }
                  else {
                    $php .= ' ';
                  }
                }
                else {
                  // Normalize whitespace.
                  $php .= ' ';
                }
                continue 2;

              default:
                if (is_string($token)) {
                  $php .= $token;
                }
                else {
                  if (str_contains($token[1], "\n")) {

                  }
                  $php .= $token[1];
                }
                continue 2;
            }
          }
          // End of segment attempt operations.
          if ($hasNestedExpression && $breakOnOperatorCandidate > 0) {
            // Try again.
            $breakOnOperator = $breakOnOperatorCandidate;
            continue;
          }
          if (true || $mode === self::MODE_LIST_INLINE) {
            if (!$this->checkLastLineLength($position, $php)) {
              if ($breakOnOperatorCandidate > 0) {
                // Try again.
                $breakOnOperator = $breakOnOperatorCandidate;
                continue;
              }
            }
            else {
              $x = 5;
            }
          }
          if ($token === ',' || $token === ';') {
            // Next segment.
            continue 2;
          }
          // End of snippet.
          break 2;
        }
      }
      // End of snippet operations.
      switch ($mode) {
        case self::MODE_ENCLOSED_VALUE:
          $php = rtrim($php, "\n ");
          if ($hasOperatorBreaks) {
            $php .= "\n" . $indentBase;
          }
          return $php;

        case self::MODE_LIST_INLINE:
          $php = rtrim($php, "\n ");
          if (!$this->checkLineLengths($position, $php)) {
            $mode = self::MODE_LIST_MULTILINE;
            continue 2;
          }
          return $php;

        case self::MODE_LIST_MULTILINE:
          $php = rtrim($php, "\n ");
          $php .= ",\n" . $indentBase;
          return $php;

        case self::MODE_FILE:
          return $php;

        case self::MODE_OTHER_MULTILINE:
          $php = rtrim($php, "\n ");
          $php .= "\n" . $indentBase;
          return $php;

        case self::MODE_OTHER_INLINE:
          $php = rtrim($php, "\n ");
          if (str_contains($php, "\n")) {
            $php .= "\n" . $indentBase;
          }
          return $php;

        default:
          throw new \RuntimeException('Unexpected value for $mode.');
      }
    }
  }


  /**
   * @param string $php
   * @param int $position
   *
   * @return int
   */
  private function calcBrPos(string $php, int $position): int {
    if (false !== $lastbrpos = strrpos($php, "\n")) {
      return strlen($php) - $lastbrpos;
    }
    else {
      return strlen($php) + $position;
    }
  }

  /**
   * @param int $position
   *   Number of chars since last known line break.
   * @param string $php
   *
   * @return bool
   *   TRUE if the lines are short enough to keep this as inline.
   */
  private function checkLastLineLength(int $position, string $php): bool {
    if ($php === '') {
      return true;
    }
    if (false === $lastbrpos = strrpos($php, "\n")) {
      // Snippet has only one line.
      // Check line length.
      $ret = $position + strlen($php) <= $this->limit;
      return $ret;
    }
    // Check last line length.
    return strlen($php) - $lastbrpos <= $this->limit;
  }

  /**
   * @param int $position
   *   Number of chars since last known line break.
   * @param string $php
   *
   * @return bool
   *   TRUE if the lines are short enough to keep this as inline.
   */
  private function checkLineLengths(int $position, string $php): bool {
    if ($php === '') {
      return true;
    }
    if (false === $lastbrpos = strrpos($php, "\n")) {
      // Snippet has only one line.
      // Check line length.
      $ret = $position + strlen($php) <= $this->limit;
      return $ret;
    }
    // Check last line length.
    $ret0 = $position + strpos($php, "\n") <= $this->limit;
    $ret1 = strlen($php) - $lastbrpos <= $this->shortLimit;
    $ret2 = strpos($php, "\n") <= $this->shortLimit;
    return $ret0 && $ret1 && $ret2;
  }

}
