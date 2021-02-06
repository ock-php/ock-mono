<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Discovery\FactoryToSTA;

use Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;

interface FactoryToSTAInterface {

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\OCUI\SchemaToAnything\Partial\SchemaToAnythingPartialInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?SchemaToAnythingPartialInterface;
}
