<?php

declare(strict_types=1);

namespace Donquixote\Ock\V2V\Id;

interface V2V_IdInterface {

  /**
   * @param string|int $id
   *
   * @return string
   */
  public function idGetPhp($id): string;

}
