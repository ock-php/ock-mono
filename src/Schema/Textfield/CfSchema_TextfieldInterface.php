<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Textfield;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Text\TextInterface;

/**
 * @todo What about limited number of characters?
 */
interface CfSchema_TextfieldInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\OCUI\Text\TextInterface|null
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
   * @return \Donquixote\OCUI\Text\TextInterface[]
   */
  public function textGetValidationErrors(string $text): array;

}
