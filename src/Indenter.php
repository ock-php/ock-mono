<?php

declare(strict_types = 1);

namespace Donquixote\CodegenTools;

class Indenter {

  private string $indentLevel = '  ';

  /**
   * @param string $php
   * @param string $indent_base
   *
   * @return string
   */
  public function autoIndent(string $php, string $indent_base = ''): string {
    $tokens = token_get_all('<?php' . "\n" . $php);
    $tokens[] = [\T_WHITESPACE, "\n"];
    // Add a special token to mark the end of the array.
    $tokens[] = '#';
    $tokens = $this->prepareTokens($tokens);

    $i = 1;
    $out = [''];
    $this->doAutoIndent($out, $tokens, $i, $indent_base);

    array_pop($out);

    return implode('', $out);
  }

  /**
   * @param array $tokens_original
   *
   * @return array
   */
  private function prepareTokens(array $tokens_original): array {

    $tokens_prepared = [];
    for ($i = 0; TRUE; ++$i) {
      $token = $tokens_original[$i];
      if ($token[0] === \T_COMMENT) {
        if (str_ends_with($token[1], "\n")) {
          // Remove trailing line break from the comment, make it a separate token instead.
          $tokens_prepared[] = [\T_COMMENT, substr($token[1], 0, -1)];
          if ($tokens_original[$i + 1][0] === \T_WHITESPACE) {
            $tokens_prepared[] = [\T_WHITESPACE, "\n" . $tokens_original[$i + 1][1]];
            ++$i;
          }
          else {
            $tokens_prepared[] = [\T_WHITESPACE, "\n"];
          }
          continue;
        }
      }

      $tokens_prepared[] = $token;

      if ($token === '#') {
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
   */
  private function doAutoIndent(array &$out, array $tokens, int &$i, string $indent_base): void {

    $indent_deeper = $indent_base . $this->indentLevel;

    while (TRUE) {
      $token = $tokens[$i];
      if (\is_string($token)) {
        switch ($token) {
          case '{':
          case '(':
          case '[':
            $out[] = $token;
            ++$i;
            $this->doAutoIndent(
              $out,
              $tokens,
              $i,
              ($tokens[$i][0] === T_WHITESPACE && $tokens[$i][1][0] === "\n")
                ? $indent_deeper
                : $indent_base,
            );
            if ('#' === $tokens[$i]) {
              return;
            }
            if (\T_WHITESPACE === $tokens[$i - 1][0]) {
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
          case \T_WHITESPACE:
            $n_linebreaks = substr_count($token[1], "\n");
            if (0 === $n_linebreaks) {
              $out[] = $token[1];
              ++$i;
              continue 2;
            }
            $out[] = str_repeat("\n", $n_linebreaks) . $indent_base;
            break;

          case \T_COMMENT:
          case \T_DOC_COMMENT:
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

}
