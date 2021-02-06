<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Translatable;

use Donquixote\OCUI\Text\TextInterface;

interface TranslatableInterface extends TextInterface {

  /**
   * @return string
   */
  public function getOriginalText(): string;

  /**
   * Gets the replacements.
   *
   * @return mixed[]
   *   Map of strings or stringable objects.
   */
  public function getReplacements(): array;

}
