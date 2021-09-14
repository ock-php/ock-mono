<?php

declare(strict_types=1);

namespace Donquixote\Ock\ValueStubToPhp;

use Donquixote\Ock\ValueStub\ValueStubInterface;

interface ValueStubToPhpInterface {

  /**
   * @param \Donquixote\Ock\ValueStub\ValueStubInterface $valueStub
   *
   * @return string
   */
  public function php(ValueStubInterface $valueStub): string;

}
