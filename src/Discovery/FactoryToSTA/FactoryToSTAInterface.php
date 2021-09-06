<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Discovery\FactoryToSTA;

use Donquixote\ObCK\Nursery\Cradle\CradleInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;

interface FactoryToSTAInterface {

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\ObCK\Nursery\Cradle\CradleInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?CradleInterface;
}
