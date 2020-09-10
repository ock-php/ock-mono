<?php
declare(strict_types=1);

namespace Donquixote\Cf\Discovery\FactoryToSTA;

use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;

interface FactoryToSTAInterface {

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?SchemaToAnythingPartialInterface;
}
