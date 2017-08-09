<?php

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldBase;
use Donquixote\Cf\V2V\String\V2V_StringInterface;

class CfSchema_ListSeparator extends CfSchema_TextfieldBase implements V2V_StringInterface {

  /**
   * @param string $text
   *
   * @return string[]
   */
  public function textGetValidationErrors($text) {
    $errors = [];

    if (strlen($text) > 20) {
      $errors[] = 'Separator cannot be longer than 20 characters.';
    }

    return $errors;
  }

  /**
   * @param string $string
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function stringGetValue($string) {
    return check_plain($string);
  }

  /**
   * @param string $string
   *
   * @return string
   */
  public function stringGetPhp($string) {
    return var_export(check_plain($string), TRUE);
  }
}
