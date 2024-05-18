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
   * @throws \Ock\Egg\Exception\ToEggException
   */
  public function classGetEgg(\ReflectionClass $reflectionClass): EggInterface;

}
