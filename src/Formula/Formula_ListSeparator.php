<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Textfield\Formula_TextfieldBase;
use Donquixote\Ock\Zoo\V2V\String\V2V_StringInterface;
use Drupal\Component\Utility\Html;

class Formula_ListSeparator extends Formula_TextfieldBase implements V2V_StringInterface {

  /**
   * @param string $text
   *
   * @return string[]
   */
  public function textGetValidationErrors($text): array {
    $errors = [];

    if (\strlen($text) > 20) {
      $errors[] = 'Separator cannot be longer than 20 characters.';
    }

    return $errors;
  }

  /**
   * @param string $string
   *
   * @return mixed
   */
  public function stringGetValue(string $string) {
    return Html::escape($string);
  }

  /**
   * @param string $string
   *
   * @return string
   */
  public function stringGetPhp(string $string): string {
    return var_export(Html::escape($string), TRUE);
  }
}
