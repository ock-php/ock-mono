<?php
declare(strict_types=1);

namespace Donquixote\Cf\Translatable;

interface TranslatableInterface {

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
