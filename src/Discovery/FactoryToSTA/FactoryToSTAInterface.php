<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery\FactoryToSTA;

use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;
use Donquixote\Ock\IncarnatorPartial\SpecificAdapterInterface;

interface FactoryToSTAInterface {

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\Ock\IncarnatorPartial\SpecificAdapterInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?SpecificAdapterInterface;

}
