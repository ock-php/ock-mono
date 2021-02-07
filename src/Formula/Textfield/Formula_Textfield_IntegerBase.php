<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Textfield;

use Donquixote\OCUI\Formula\StringVal\Formula_StringVal;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Util\PhpUtil;
use Donquixote\OCUI\Zoo\V2V\String\V2V_StringInterface;

abstract class Formula_Textfield_IntegerBase extends Formula_TextfieldBase implements V2V_StringInterface {

  /**
   * @return \Donquixote\OCUI\Formula\StringVal\Formula_StringVal
   */
  public function createValFormula(): Formula_StringVal {
    return new Formula_StringVal($this, $this);
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
