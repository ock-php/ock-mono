<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Textfield;

use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Schema\StringVal\CfSchema_StringVal;
use Donquixote\Cf\Util\PhpUtil;
use Donquixote\Cf\Zoo\V2V\String\V2V_StringInterface;

abstract class CfSchema_Textfield_IntegerBase extends CfSchema_TextfieldBase implements V2V_StringInterface {

  /**
   * @return \Donquixote\Cf\Schema\StringVal\CfSchema_StringVal
   */
  public function createValSchema(): CfSchema_StringVal {
    return new CfSchema_StringVal($this, $this);
  }

  /**
   * {@inheritdoc}
   */
  public function textGetValidationErrors($text): array {

    $errors = [];

    if ((string)(int)$text !== (string)$text) {
      $errors[] = "Value must be an integer.";
      return $errors;
    }

    return $this->numberGetValidationErrors((int)$text);
  }

  /**
   * @param int $number
   *
   * @return string[]
   */
  protected function numberGetValidationErrors(int $number): array {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function stringGetPhp(string $string): string {

    if ([] !== $errors = $this->textGetValidationErrors($string)) {
      return PhpUtil::incompatibleConfiguration(reset($errors));
    }

    return var_export((int)$string, TRUE);
  }
}
