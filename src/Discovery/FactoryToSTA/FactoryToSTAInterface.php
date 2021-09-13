<?php
declare(strict_types=1);

namespace Donquixote\Ock\Discovery\FactoryToSTA;

use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

interface FactoryToSTAInterface {

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\Ock\Incarnator\IncarnatorInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?IncarnatorInterface;
}
