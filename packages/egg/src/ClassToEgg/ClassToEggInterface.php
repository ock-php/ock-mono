<?php

declare(strict_types = 1);

namespace Ock\Egg\ClassToEgg;

use Ock\Egg\Egg\EggInterface;

interface ClassToEggInterface {

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return \Ock\Egg\Egg\EggInterface
   *
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  public function classGetEgg(\ReflectionClass $reflectionClass): EggInterface;

}
