<?php

declare(strict_types=1);

namespace Donquixote\Ock\V2V\Value;

interface V2V_ValueInterface {

  /**
   * @param string $php
   *
   * @return string
   *
   * @throws \Donquixote\Ock\Exception\GeneratorException
   */
  public function phpGetPhp(string $php): string;

}
