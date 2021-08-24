<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Fallback;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface Formula_FallbackInterface extends FormulaInterface {

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return string
   */
  public function getFallbackPhp(): string;

}
