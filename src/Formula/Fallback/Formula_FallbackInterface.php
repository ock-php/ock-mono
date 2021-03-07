<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Fallback;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface Formula_FallbackInterface extends FormulaInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return string
   */
  public function getFallbackPhp(): string;

}
