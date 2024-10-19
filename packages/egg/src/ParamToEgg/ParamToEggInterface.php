<?php

declare(strict_types = 1);

namespace Ock\Egg\ParamToEgg;

use Ock\Egg\Egg\EggInterface;

interface ParamToEggInterface {

  const SERVICE_TAG = self::class;

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return \Ock\Egg\Egg\EggInterface|null
   *
   * @throws \Ock\Egg\Exception\ToEggException
   */
  public function paramGetEgg(\ReflectionParameter $parameter): ?EggInterface;

}
