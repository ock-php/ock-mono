<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Zoo\V2V\Id;

interface V2V_IdInterface {

  /**
   * @param string|int $id
   *
   * @return string
   */
  public function idGetPhp($id): string;

}
