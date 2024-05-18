<?php

declare(strict_types = 1);

namespace Ock\Egg\ParamToEgg;

use Donquixote\ClassDiscovery\Util\ReflectionTypeUtil;
use Ock\Egg\Egg\Egg_ServiceId;
use Ock\Egg\Egg\EggInterface;

class ParamToEgg_ParamTypeAsServiceId implements ParamToEggInterface {

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return \Ock\Egg\Egg\EggInterface|null
   * @throws \Donquixote\ClassDiscovery\Exception\MalformedDeclarationException
   */
  public function paramGetEgg(\ReflectionParameter $parameter): ?EggInterface {
    $class = ReflectionTypeUtil::getClassLikeType($parameter);
    if ($class === NULL) {
      return NULL;
    }
    return new Egg_ServiceId($class);
  }

}
