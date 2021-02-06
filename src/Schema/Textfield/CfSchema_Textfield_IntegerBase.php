<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Textfield;

use Donquixote\OCUI\Schema\StringVal\CfSchema_StringVal;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Util\PhpUtil;
use Donquixote\OCUI\Zoo\V2V\String\V2V_StringInterface;

abstract class CfSchema_Textfield_IntegerBase extends CfSchema_TextfieldBase implements V2V_StringInterface {

  /**
   * @return \Donquixote\OCUI\Schema\StringVal\CfSchema_StringVal
   */
  public function createValSchema(): CfSchema_StringVal {
    return new CfSchema_StringVal($this, $this);
  }

  /**
   * {@inheritdoc}
   */
  public function textGetValidationErrors(string $text): array {

    $errors = [];

    if ((string) (int) $text !== $text) {
      $errors[] = Text::t("Value must be an integer.");
      return $errors;
    }

    return $this->numberGetValidationErrors((int)$text);
  }

  /**
   * @param int $number
   *
   * @return \Donquixote\OCUI\Text\TextInterface[]
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
