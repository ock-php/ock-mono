<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\V2V\Boolean;

interface V2V_BooleanInterface {

  /**
   * @return string
   */
  public function getTruePhp(): string;

  /**
   * @return string
   */
  public function getFalsePhp(): string;

}
