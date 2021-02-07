<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Discovery\FactoryToSTA;

use Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;

interface FactoryToSTAInterface {

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?FormulaToAnythingPartialInterface;
}
