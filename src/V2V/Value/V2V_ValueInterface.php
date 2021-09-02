<?php
declare(strict_types=1);

namespace Donquixote\ObCK\V2V\Value;

interface V2V_ValueInterface {

  /**
   * @param string $php
   *
   * @return string
   */
  public function phpGetPhp(string $php): string;

}
