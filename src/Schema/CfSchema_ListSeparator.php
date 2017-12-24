<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldBase;
use Donquixote\Cf\Zoo\V2V\String\V2V_StringInterface;
use Drupal\Component\Utility\Html;

class CfSchema_ListSeparator extends CfSchema_TextfieldBase implements V2V_StringInterface {

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
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function stringGetValue($string) {
    return Html::escape($string);
  }

  /**
   * @param string $string
   *
   * @return string
   */
  public function stringGetPhp($string): string {
    return var_export(Html::escape($string), TRUE);
  }
}
