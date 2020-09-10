<?php
declare(strict_types=1);

namespace Donquixote\Cf\Translatable;

interface TranslatableInterface {

  /**
   * @return string
   */
  public function getOriginalText(): string;

  /**
   * @return string[]
   */
  public function getReplacements(): array;

}
