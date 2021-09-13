<?php
declare(strict_types=1);

namespace Donquixote\Ock\Discovery\FactoryToSTA;

use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;
use Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface;

interface FactoryToSTAInterface {

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?IncarnatorPartialInterface;
}
