<?php

declare(strict_types = 1);

namespace Donquixote\DID\CodegenTools;

class LineBreaker {

  public function breakLongLines(string $php): string {
    $php_full = '<?php' . "\n" . $php . "\n";
    $tokens = token_get_all($php_full);
    $tokens[] = '#';
    for ($i = 0;; ++$i) {
      $token = $tokens[$i];
    }

  }

  private function processSection(array $tokens, int $iBegin, string $endchar): string {
    $php = '';
    for ($i = $iBegin;; ++$i) {
      $token = $tokens[$i];
      if (is_string($token)) {
        switch ($token) {
          case '(':
            $php .= $this->processSection($tokens, $i, ']');
            break;

          case '{':
            $php .= $this->processSection($tokens, $i, ']');
            break;

          case '[':
            $php .= $this->processSection($tokens, $i, ']');
            break;

          case $endchar:
        }
      }
      else {
        switch ($token[0]) {

        }
      }
    }
  }

}
