<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Textfield;

class CfSchema_Textfield_NoValidation implements CfSchema_TextfieldInterface {

  /**
   * {@inheritdoc}
   */
  public function getDescription(): ?string {
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
