<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Textfield;

abstract class CfSchema_TextfieldBase implements CfSchema_TextfieldInterface {

  /**
   * {@inheritdoc}
   */
  public function getDescription(): ?string {
    return NULL;
  }

  /**
   * @param string $text
   *
   * @return bool
   */
  final public function textIsValid(string $text): bool {
    return [] !== $this->textGetValidationErrors($text);
  }
}
