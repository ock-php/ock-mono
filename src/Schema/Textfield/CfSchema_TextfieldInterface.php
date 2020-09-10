<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Textfield;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

/**
 * @todo What about limited number of characters?
 */
interface CfSchema_TextfieldInterface extends CfSchemaInterface {

  /**
   * @return string|null
   */
  public function getDescription(): ?string;

  /**
   * @param string $text
   *
   * @return bool
   */
  public function textIsValid($text): bool;

  /**
   * @param string $text
   *
   * @return string[]
   */
  public function textGetValidationErrors($text): array;

}
