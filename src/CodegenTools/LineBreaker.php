<?php

declare(strict_types = 1);

namespace Donquixote\DID\CodegenTools;

class LineBreaker {

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
    $tokens = token_get_all('<?php ' . $php);
    // Append two terminating tokens to allow lookaheads.
    $tokens[] = '#';
    $tokens[] = '#';
    $i = 0;
    $phpFormatted = $this->formatSection($tokens, $i, '', 0, TRUE);
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
   * @param bool $formatAsIs
   *
   * @return string
   */
  private function formatSection(array $tokens, int &$i, string $indent, int $position, bool $formatAsIs): string {
    /** @readonly $iStart */
    $iStart = $i;
    $indentBase = $indent;
    $formatAsMultilineList = false;
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
        $breakOnOperator = 99;
        // Multiple attempts to format the segment.
        for ($iSegmentAttempt = 0;; ++$iSegmentAttempt) {
          if ($iSegmentAttempt >= 5) {
            throw new \RuntimeException("Infinite loop in segment $iSegment.");
          }
          $indent = $formatAsMultilineList
            ? $indentBase . $this->indentLevel
            : $indentBase;
          $php = $phpAtSegmentStart;
          $i = $iCommaOrStart;
          if ($iSegment > 0) {
            $php .= $tokens[$iCommaOrStart];
          }
          ++$i;
          if ($tokens[$i][0] === T_WHITESPACE) {
            if ($formatAsIs) {
              if ($nBr = substr_count($tokens[$i][1], "\n")) {
                $php .= str_repeat("\n", $nBr) . $indent;
              }
            }
            ++$i;
          }
          $iSegmentStart = $i;
          if ($formatAsMultilineList) {
            $php .= "\n" . $indent;
          }
          elseif (!$formatAsIs && $iSegment > 0) {
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
                  $token[0] === '{',
                );
                if (str_contains($nested, "\n")) {
                  if (!str_starts_with($nested, "\n")) {
                    $hasNestedExpression = true;
                  }
                  if (!$formatAsMultilineList && !$formatAsIs) {
                    if (str_contains($php, "\n")) {
                      // Try again, as multiline.
                      $formatAsMultilineList = true;
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
                if (!$formatAsIs) {
                  // Start over.
                  $formatAsIs = true;
                  continue 5;
                }
                break 2;

              case ',':
                // End of segment.
                if (!$formatAsMultilineList && !$formatAsIs && $hasOperatorBreaks) {
                  // Try again.
                  $formatAsMultilineList = true;
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
                  $php = $phpAtSegmentStart;
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
                if (($formatAsMultilineList || $formatAsIs || $iSegment === 0)
                  && (NULL !== $precedence = $this->operatorMap[$tokens[$i + 1][0]] ?? null)
                  && $tokens[$i + 2][0] === T_WHITESPACE
                ) {
                  // An operator surrounded by whitespace on both sides is
                  // assumed to be binary. It is up to the code generator to
                  // make sure this is the case.
                  if ($breakOnOperator <= $precedence) {
                    $indent = $indentBase . $this->indentLevel . $this->indentLevel;
                    $php .= "\n" . $indentBase . $this->indentLevel;
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
                if ($formatAsIs) {
                  $php .= $token[1];
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
          if ($formatAsMultilineList || $formatAsIs || $iSegment === 0) {
            if (!$this->checkLineLengths($position, $php)) {
              if ($breakOnOperatorCandidate > 0) {
                // Try again.
                $breakOnOperator = $breakOnOperatorCandidate;
                continue;
              }
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
      if (!$formatAsMultilineList && !$formatAsIs) {
        if ($iSegment > 0
          && !$this->checkLineLengths($position, $php)
        ) {
          // Try again.
          $formatAsMultilineList = true;
          continue;
        }
      }
      if (!$formatAsIs) {
        // Remove custom trailing whitespace.
        $php = rtrim($php, "\n ");
      }
      if ($token !== '#') {
        if ($formatAsMultilineList) {
          $php .= ",\n" . $indentBase;
        }
        elseif ($hasOperatorBreaks) {
          $php .= "\n" . $indentBase;
        }
      }
      return $php;
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
