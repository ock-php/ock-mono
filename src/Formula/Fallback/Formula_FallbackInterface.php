<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Fallback;

use Donquixote\Ock\Core\Formula\FormulaInterface;

interface Formula_FallbackInterface extends FormulaInterface {

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return string
   */
  public function getFallbackPhp(): string;

}
