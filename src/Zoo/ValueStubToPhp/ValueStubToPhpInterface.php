<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\ValueStubToPhp;

use Donquixote\Cf\Zoo\ValueStub\ValueStubInterface;

interface ValueStubToPhpInterface {

  /**
   * @param \Donquixote\Cf\Zoo\ValueStub\ValueStubInterface $valueStub
   *
   * @return string
   */
  public function php(ValueStubInterface $valueStub): string;

}
