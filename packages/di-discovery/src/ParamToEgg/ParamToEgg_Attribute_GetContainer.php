<?php

declare(strict_types = 1);

namespace Ock\DID\ParamToEgg;

use Ock\ClassDiscovery\Util\AttributesUtil;
use Ock\DID\Attribute\Parameter\GetContainer;
use Ock\Egg\Egg\Egg_Container;
use Ock\Egg\Egg\EggInterface;
use Ock\Egg\ParamToEgg\ParamToEggInterface;

class ParamToEgg_Attribute_GetContainer implements ParamToEggInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetEgg(\ReflectionParameter $parameter): ?EggInterface {
    return AttributesUtil::hasSingle($parameter, GetContainer::class)
      ? new Egg_Container()
      : NULL;
  }

}
