<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldBase;

class CfSchema_TagNameFree extends CfSchema_TextfieldBase {

  /**
   * @param string $text
   *
   * @return string[]
   */
  public function textGetValidationErrors($text) {

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
