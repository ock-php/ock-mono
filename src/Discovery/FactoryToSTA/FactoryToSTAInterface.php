<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Discovery\FactoryToSTA;

use Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartialInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;

interface FactoryToSTAInterface {

  /**
   * @param \Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface $factory
   *
   * @return \Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartialInterface|null
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?FormulaToAnythingPartialInterface;
}
