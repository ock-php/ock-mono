<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Textfield;

use Donquixote\ObCK\Text\TextInterface;

abstract class Formula_TextfieldBase implements Formula_TextfieldInterface {

  /**
   * {@inheritdoc}
   */
  public function getDescription(): ?TextInterface {
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
