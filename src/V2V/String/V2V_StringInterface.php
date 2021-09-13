<?php
declare(strict_types=1);

namespace Donquixote\Ock\V2V\String;

interface V2V_StringInterface {

  /**
   * @param string $string
   *
   * @return string
   */
  public function stringGetPhp(string $string): string;

}
