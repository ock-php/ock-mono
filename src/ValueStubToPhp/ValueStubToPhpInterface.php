<?php
declare(strict_types=1);

namespace Donquixote\ObCK\ValueStubToPhp;

use Donquixote\ObCK\ValueStub\ValueStubInterface;

interface ValueStubToPhpInterface {

  /**
   * @param \Donquixote\ObCK\ValueStub\ValueStubInterface $valueStub
   *
   * @return string
   */
  public function php(ValueStubInterface $valueStub): string;

}
