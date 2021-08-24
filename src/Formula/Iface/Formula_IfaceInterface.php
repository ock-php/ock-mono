<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Iface;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface Formula_IfaceInterface extends FormulaInterface {

  /**
   * @return string
   */
  public function getInterface(): string;

  /**
   * @return bool
   */
  public function allowsNull(): bool;

}
