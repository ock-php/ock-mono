<?php

declare(strict_types=1);

namespace Donquixote\Ock\V2V\Value;

use Donquixote\Ock\Util\PhpUtil;

class V2V_Value_CallSingleParam implements V2V_ValueInterface {

  /**
   * Constructor.
   *
   * @param string $fqn
   */
  public function __construct(private readonly string $fqn) {}

  /**
   * {@inheritdoc}
   */
  public function phpGetPhp(string $php): string {
    return PhpUtil::phpCallFqn($this->fqn, [$php]);
  }

}
