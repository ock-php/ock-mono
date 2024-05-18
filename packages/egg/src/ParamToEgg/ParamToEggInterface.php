<?php

declare(strict_types = 1);

namespace Ock\Egg\ParamToEgg;

use Ock\Egg\Egg\EggInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(self::SERVICE_TAG)]
interface ParamToEggInterface {

  const SERVICE_TAG = self::class;

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return \Ock\Egg\Egg\EggInterface|null
   *
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  public function paramGetEgg(\ReflectionParameter $parameter): ?EggInterface;

}
