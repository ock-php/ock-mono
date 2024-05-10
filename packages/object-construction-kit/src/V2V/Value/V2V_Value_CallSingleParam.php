<?php

declare(strict_types=1);

namespace Donquixote\Ock\V2V\Value;

use Donquixote\CodegenTools\Util\CodeGen;
use Donquixote\CodegenTools\Util\PhpUtil;

class V2V_Value_CallSingleParam implements V2V_ValueInterface {

  /**
   * Constructor.
   *
   * @param string $fqn
   */
  public function __construct(private readonly string $fqn) {}

  /**
   * {@inheritdoc}
   * @param mixed $conf
   */
  public function phpGetPhp(string $php, mixed $conf): string {
    return CodeGen::phpCallFqn($this->fqn, [$php]);
  }

}