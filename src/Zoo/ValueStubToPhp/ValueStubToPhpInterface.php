<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Zoo\ValueStubToPhp;

use Donquixote\ObCK\Zoo\ValueStub\ValueStubInterface;

interface ValueStubToPhpInterface {

  /**
   * @param \Donquixote\ObCK\Zoo\ValueStub\ValueStubInterface $valueStub
   *
   * @return string
   */
  public function php(ValueStubInterface $valueStub): string;

}
