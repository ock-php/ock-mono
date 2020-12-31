<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Textfield;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Text\TextInterface;

/**
 * @todo What about limited number of characters?
 */
interface CfSchema_TextfieldInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\Cf\Text\TextInterface|null
   */
  public function getDescription(): ?TextInterface;

  /**
   * @param string $text
   *
   * @return bool
   */
  public function textIsValid(string $text): bool;

  /**
   * @param string $text
   *
   * @return string[]
   */
  public function textGetValidationErrors(string $text): array;

}
