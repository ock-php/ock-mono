<?php
declare(strict_types=1);

namespace Donquixote\Ock\Discovery\FactoryToSTA;

use Donquixote\Ock\Nursery\Cradle\CradleInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;

interface FactoryToSTAInterface {

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\Ock\Nursery\Cradle\CradleInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?CradleInterface;
}
