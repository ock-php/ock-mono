<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Formula\Textfield\Formula_TextfieldBase;

class Formula_TagNameFree extends Formula_TextfieldBase {

  /**
   * @param string $text
   *
   * @return string[]
   */
  public function textGetValidationErrors($text): array {

    $errors = [];

    if (!preg_match('/^[\w]+$/', $text)) {
      $errors[] = 'The tag name contains invalid characters.';
    }

    if (\strlen($text) >= 14) {
      $errors[] = 'The tag name seems to be too long.';
    }

    return $errors;
  }
}
