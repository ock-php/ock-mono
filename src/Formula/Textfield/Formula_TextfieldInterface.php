<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Textfield;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Text\TextInterface;

/**
 * @todo What about limited number of characters?
 */
interface Formula_TextfieldInterface extends FormulaInterface {

  /**
   * @return \Donquixote\ObCK\Text\TextInterface|null
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
   * @return \Donquixote\ObCK\Text\TextInterface[]
   */
  public function textGetValidationErrors(string $text): array;

}
