<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\ValueStubToPhp;

use Donquixote\OCUI\Zoo\ValueStub\ValueStubInterface;

interface ValueStubToPhpInterface {

  /**
   * @param \Donquixote\OCUI\Zoo\ValueStub\ValueStubInterface $valueStub
   *
   * @return string
   */
  public function php(ValueStubInterface $valueStub): string;

}
