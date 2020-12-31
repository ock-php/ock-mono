<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Textfield;

use Donquixote\Cf\Text\TextInterface;

class CfSchema_Textfield_NoValidation implements CfSchema_TextfieldInterface {

  /**
   * {@inheritdoc}
   */
  public function getDescription(): ?TextInterface {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function textIsValid(string $text): bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function textGetValidationErrors(string $text): array {
    return [];
  }
}
