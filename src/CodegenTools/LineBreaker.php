<?php

declare(strict_types = 1);

namespace Donquixote\DID\CodegenTools;

use Donquixote\DID\Exception\CodegenException;

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
   * @throws \Donquixote\DID\Exception\CodegenException
   */
  public function breakLongLines(string $php): string {
    if ($this->operatorMap === []) {
      $this->operatorMap = $this->buildOperatorMap();
    }
    $tokens = token_get_all('<?php ' . $php);
    // Append two terminating tokens to allow lookaheads.
    $tokens[] = '#';
    $tokens[] = '#';
    $i = 1;
    $phpFormatted = '';
    if ($tokens[$i][0] === \T_WHITESPACE) {
      if ($n = substr_count($tokens[$i][1], "\n")) {
        $phpFormatted .= str_repeat("\n", $n);
      }
      ++$i;
    }
    $i = 0;
    $phpFormatted = $this->formatEnclosedSnippet($tokens, $i, '', 0, true, []);
    # $phpFormatted .= $this->formatScope($tokens, $i, '', 0, []);
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
   * Formats a code section which already has the needed line breaks.
   *
   * @param array $tokens
   * @param int $i
   *   Before: First symbol in scope.
   *   After: Position of ending '}' or '#'.
   * @param string $indent
   * @param int $position
   * @param array $sofar
   *
   * @return string
   *   Prettified php code, but without opening '{' or closing '}'/'#'.
   *
   * @throws \Donquixote\DID\Exception\CodegenException
   */
  public function formatScope(array $tokens, int &$i, string $indent, int $position, array $sofar): string {
    assert($tokens[$i][0] !== \T_WHITESPACE);
    $php = '';
    for (;;) {
      $expression = $this->formatExpressionOrStatement(
        $tokens,
        $i,
        $indent,
        $this->calcBrPos($php, $position),
        $expressionIsMultiline,
        [...$sofar, '[S] ' . $php],
      );
      $php .= $expression;
      // Get the token that ends the expression. This could be e.g. ')' or ','.
      $token = $tokens[$i];
      if (\is_string($token)) {
        switch ($token) {
          case '}':
          case ')':
          case ']':
          case '#':
            // The scope ends here.
            return $php;

          case ',':
          case ';':
            $php .= $token;
            ++$i;
            if ($tokens[$i][0] === \T_WHITESPACE) {
              $php .= $tokens[$i][1];
              ++$i;
            }
            continue 2;

          default:
            throw new CodegenException(sprintf('Unexpected token %s.', $token));
        }
      }
      throw new CodegenException(
        sprintf(
          'Unexpected token %s / %s.',
          token_name($token[0]),
          json_encode($token[1])
        ),
      );
    }
  }

  /**
   * Formats a section starting with '[' or '('.
   *
   * @param array $tokens
   * @param int $i
   * @param string $indent
   * @param int $position
   * @param array $sofar
   *
   * @return string
   */
  private function formatEnclosedSnippet(array $tokens, int &$i, string $indent, int $position, bool $formatAsIs, array $sofar): string {
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
                $nested = $this->formatEnclosedSnippet(
                  $tokens,
                  $i,
                  $indent,
                  $this->calcBrPos($php, $position),
                  $token[0] === '{',
                  [...$sofar, '[E] ' . $php],
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
            if (!$this->checkLineLengths($position, $php, $sofar)) {
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
          && !$this->checkLineLengths($position, $php, $sofar)
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
   * @param array $tokens
   * @param int $i
   * @param string $indent
   * @param int $position
   *   Number of chars since last known line break.
   * @param bool $listIsMultiline
   *
   * @return string
   * @throws \Donquixote\DID\Exception\CodegenException
   */
  private function formatEnclosedList(array $tokens, int &$i, string $indent, int $position, bool &$listIsMultiline = NULL, array $sofar): string {
    ++$i;
    $leadingLinebreaks = '';
    if ($tokens[$i][0] === \T_WHITESPACE) {
      if ($n = substr_count($tokens[$i][1], "\n")) {
        $leadingLinebreaks = str_repeat("\n", $n);
      }
      ++$i;
    }
    $iStart = $i;
    if (!$leadingLinebreaks) {
      // Try inline.
      $php = $this->formatListElements($tokens, $i, $indent, $position, false, $sofar);
      if ($php !== false && $this->checkLineLengths($position, $php, [...$sofar, '[L?] ' . $php])) {
        if (!in_array($tokens[$i][0], [')', '}', ']', '#'])) {
          assert(false);
        }
        $listIsMultiline = false;
        return $php;
      }
      // Do it again, this time with multiline enabled.
      $i = $iStart;
      $leadingLinebreaks = "\n";
    }
    // Build multiline version.
    $php = $this->formatListElements($tokens, $i, $indent . $this->indentLevel, $position, true, $sofar);
    if ($php === false) {
      throw new \RuntimeException('Unexpected value false.');
    }
    if (!in_array($tokens[$i][0], [')', '}', ']', '#'])) {
      assert(false);
    }
    if ($php === '') {
      return '';
    }
    if (!str_ends_with($php, ',')) {
      // Add trailing comma.
      $php .= ',';
    }
    $listIsMultiline = true;
    return $leadingLinebreaks . $php;
  }

  /**
   * Formats a list of function arguments, array values or similar.
   *
   * @param array $tokens
   *   Complete list of tokens, terminated with '#'.
   * @param int $i
   *   Before: Start of first expression or statement.
   *   After: Symbol that ends the list. One of ')', ']', '}', '#'.
   * @param string $indent
   * @param int $position
   *   Number of chars since last known line break.
   * @param bool $multiline
   * @param array $sofar
   *
   * @return string|false
   *
   * @throws \Donquixote\DID\Exception\CodegenException
   */
  private function formatListElements(array $tokens, int &$i, string $indent, int $position, bool $multiline, array $sofar): string|false {
    assert($tokens[$i][0] !== \T_WHITESPACE);
    $php = '';
    for ($first = true;; $first = false) {
      $phpBkp = $php;
      if ($multiline) {
        if (!$first && !str_ends_with($php, "\n")) {
          $php .= "\n";
        }
        $php .= $indent;
      }
      elseif (!$first) {
        $php .= ' ';
      }
      $expression = $this->formatExpressionOrStatement(
        $tokens,
        $i,
        $indent,
        strlen($indent),
        $expressionIsMultiline,
        [...$sofar, ($multiline ? '[L] ' : '[Li]') . $php],
      );
      // Strip leading and trailing whitespace from the expression.
      $expression = trim($expression, "\n\t ");
      if ($expression === '') {
        // Remove all the line breaks we added before.
        $php = $phpBkp;
      }
      elseif (!$multiline) {
        if (str_contains($expression, "\n") && str_contains($php, "\n")) {
          // Allow only one multi-line expression in an inline list.
          return false;
        }
      }
      $php .= $expression;
      // Get the token that ends the expression. This could be e.g. ')' or ','.
      $token = $tokens[$i];
      if (\is_string($token)) {
        switch ($token) {
          case '}':
          case ')':
          case ']':
            // The list ends here.
            if (!$multiline && $expression === '') {
              $php = rtrim($php, ',');
            }
            return $php;

          case ',':
          case ';':
            if ($expression === '') {
              throw new CodegenException(sprintf('Missing expression or statement before %s', $token));
            }
            $php .= $token;
            ++$i;
            if ($tokens[$i][0] === \T_WHITESPACE) {
              if (str_contains($tokens[$i][1], "\n")) {
                // Existing line breaks force the list to be multiline.
                if (!$multiline) {
                  return false;
                }
              }
              ++$i;
            }
            continue 2;

          case '#':
            // The entire code ends here.
            return $php;

          default:
            throw new CodegenException(sprintf('Unexpected token %s.', $token));
        }
      }
      throw new CodegenException(
        sprintf(
          'Unexpected token %s / %s.',
          token_name($token[0]),
          json_encode($token[1])
        ),
      );
    }
  }

  /**
   * Formats an expression with line breaks before binary operators.
   *
   * @param list<string|array{int, string, int}> $tokens
   * @param int $i
   * @param string $indent
   * @param int $position
   *   Number of chars since last known line break.
   * @param bool|null $expressionIsMultiline
   *
   * @return string
   *
   * @throws \Donquixote\DID\Exception\CodegenException
   */
  private function formatExpressionOrStatement(array $tokens, int &$i, string $indent, int $position, bool &$expressionIsMultiline = null, array $sofar): string {
    $iStart = $i;
    $php = $this->doFormatExpressionOrStatement($tokens, $i, $indent, $position, false, $sofar);
    $expressionIsMultiline = false;
    if ($php !== false && $this->checkLineLengths($position, $php, [...$sofar, '[E?] ' . $php])) {
      return $php;
    }
    $i = $iStart;
    $php = $this->doFormatExpressionOrStatement($tokens, $i, $indent, $position, true, $sofar);
    if ($php === false) {
      throw new \RuntimeException('Unexpected value false.');
    }
    $expressionIsMultiline = true;
    return $php;
  }

  /**
   * Formats an expression with line breaks before binary operators.
   *
   * @param list<string|array{int, string, int}> $tokens
   * @param int $i
   * @param string $indent
   * @param int $position
   *   Number of chars since last known line break.
   * @param bool $multiline
   *
   * @return string|false
   *
   * @throws \Donquixote\DID\Exception\CodegenException
   */
  private function doFormatExpressionOrStatement(array $tokens, int &$i, string $indent, int $position, bool $multiline, array $sofar): string|false {
    $nextTokenIsValue = true;
    $php = '';
    for (;;) {
      $token = $tokens[$i];
      if (\is_string($token)) {
        switch ($token) {
          case '{':
          case '(':
          case '[':
            $php .= $token;
            if (($token === '(' && $nextTokenIsValue) || ($token === '[' && !$nextTokenIsValue)) {
              // This is a parentheses expression or an array index expression.
              ++$i;
              // Process initial whitespace.
              if ($tokens[$i][0] === \T_WHITESPACE) {
                $whitespace = $tokens[$i][1];
                if ($nBreaks = substr_count($whitespace, "\n")) {
                  if (!$multiline) {
                    return false;
                  }
                  $php .= str_repeat("\n", $nBreaks) . $indent;
                }
                ++$i;
              }
              $childPhp = $this->formatExpressionOrStatement(
                $tokens,
                $i,
                $indent,
                $this->calcBrPos($php, $position),
                $childIsMultiline,
                [...$sofar, ($multiline ? '[E] ' : '[Ei]') . $php],
              );
            }
            else {
              $childPhp = $this->formatEnclosedList(
                $tokens,
                $i,
                $indent,
                $this->calcBrPos($php, $position),
                $childIsMultiline,
                [...$sofar, ($multiline ? '[E] ' : '[Ei]') . $php],
              );
            }
            $php .= $childPhp;
            if ('#' === $tokens[$i]) {
              // @todo Check if EOF is allowed here.
              break 2;
            }
            else {
              if ($childIsMultiline) {
                if (!str_ends_with($php, "\n")) {
                  $php .= "\n";
                }
                $php .= $indent;
              }
              $php .= $tokens[$i];
            }
            $nextTokenIsValue = false;
            break;

          case '}':
          case ')':
          case ']':
          case ',':
          case ';':
            break 2;

          case '#':
            break 2;

            // @todo Detect unary operator '-'.
          case '-':
          case '+':
          case '*':
          case '/':
          case '.':
          case '=>':
            // @todo Add more binary operators.
            if ($multiline && !$nextTokenIsValue) {
              $php = rtrim($php, ' ');
              if (!str_ends_with($php, "\n")) {
                $php = rtrim($php, ' ') . "\n";
              }
              $php .= $indent . $this->indentLevel;
            }
            $php .= $token;
            $nextTokenIsValue = true;
            break;

          default:
            $php .= $token;
            $nextTokenIsValue = false;
            break;
        }
      }
      else {
        switch ($token[0]) {
          case \T_WHITESPACE:
            // Optimize the condition for the most common case ' '.
            if ($token[1] === ' ' || !str_contains($token[1], "\n")) {
              if ($php !== '' && !str_ends_with($php, "\n") && !str_ends_with($php, ' ')) {
                $php .= ' ';
              }
            }
            elseif ($multiline) {
              // Don't remove existing line breaks.
              if (str_ends_with($php, "\n")) {
                $php .= str_repeat("\n", substr_count($token[1], "\n") - 1);
              }
              else {
                $php = rtrim($php, ' ') . str_repeat("\n", substr_count($token[1], "\n"));
              }
            }
            else {
              return false;
            }
            break;

          /** @noinspection PhpMissingBreakStatementInspection */
          case \T_COMMENT:
            if (!str_starts_with($token[1], '/*')) {
              if (!$multiline && $php !== '') {
                // Inline code cannot contain a comment in between.
                return false;
              }
              // This is a one-line comment.
              if (!str_ends_with($php, "\n")) {
                $php = rtrim($php, ' ') . "\n";
              }
              break;
            }
            // Fall through.
          case \T_DOC_COMMENT:
            if (!$multiline && $php !== '') {
              // Inline code cannot contain a comment in between.
              return false;
            }
            // Comments must start on a new line.
            $php = rtrim($php, ' ') . "\n" . $indent;
            // Every line of a `/**/` comment must be indented.
            $php .= preg_replace("@ *\\n *\\*@", "\n" . $indent . ' *', $token[1]);
            break;

          case \T_RETURN:
            $php .= $token[1];
            $nextTokenIsValue = true;
            break;

          default:
            $php .= $token[1];
            $nextTokenIsValue = false;
            break;
        }
      }

      ++$i;
    }

    return $php;
  }

  /**
   * @param array $tokens
   * @param int $i
   * @param string $indent
   * @param int $position
   * @param bool $multiline
   * @param array $sofar
   *
   * @return string
   *   The complete php code to insert between the brackets or parens.
   */
  private function formatEnclosedExpression(array $tokens, int &$i, string $indent, int $position, bool $multiline, array $sofar): string {
    assert(in_array($tokens[$i][0], ['(', '[']));
    ++$i;
    $initialBreaks = '';
    // Process initial pre-existing line breaks.
    if ($tokens[$i][0] === \T_WHITESPACE) {
      $whitespace = $tokens[$i][1];
      if ($nBreaks = substr_count($whitespace, "\n")) {
        $initialBreaks = str_repeat("\n", $nBreaks) . $indent;
        $position = 0;
      }
      ++$i;
    }
    $php = $this->formatExpressionOrStatement(
      $tokens,
      $i,
      $indent,
      $position,
      $expressionIsMultiline,
      [...$sofar, ($multiline ? '[E] ' : '[Ei]') . $php],
    );
    assert(trim($php, "\n ") !== '');
    if ($expressionIsMultiline) {
      $php .= "\n" . $indent;
    }
    return $php;
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
  private function checkLineLengths(int $position, string $php, array $sofar): bool {
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
    return $ret0 && $ret1;
  }

}
