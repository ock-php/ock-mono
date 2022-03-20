<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery\FactoryDiscovery;

use Donquixote\Ock\Discovery\FactoryVisitor\FactoryVisitorInterface;

interface FactoryDiscoveryInterface {

  /**
   * @param \Donquixote\Ock\Discovery\FactoryVisitor\FactoryVisitorInterface $visitor
   *
   * @throws \ReflectionException
   */
  public function visitAll(FactoryVisitorInterface $visitor): void;

}
