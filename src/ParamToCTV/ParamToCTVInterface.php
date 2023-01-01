<?php

declare(strict_types = 1);

namespace Donquixote\DID\ParamToCTV;

use Donquixote\DID\ContainerToValue\ContainerToValueInterface;

interface ParamToCTVInterface {

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface|null
   *
   * @throws \Donquixote\DID\Exception\DiscoveryException
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?ContainerToValueInterface;

}
