<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Drupal\Component\Utility\Html;
use Ock\Ock\Formula\Textfield\Formula_TextfieldBase;
use Ock\Ock\V2V\String\V2V_StringInterface;

class Formula_ListSeparator extends Formula_TextfieldBase implements V2V_StringInterface {

  /**
   * @param string $text
   *
   * @return string[]
   */
  public function textGetValidationErrors(string $text): array {
    $errors = [];

    if (\strlen($text) > 20) {
      $errors[] = 'Separator cannot be longer than 20 characters.';
    }

    return $errors;
  }

  /**
   * @param string $string
   *
   * @return string
   */
  public function stringGetPhp(string $string): string {
    // @todo The escape() seems wrong, depending what this is used for.
    return var_export(Html::escape($string), TRUE);
  }
}
