<?php

declare(strict_types = 1);

namespace Drupal\ock\DI\ParamToServiceArg;

use Ock\DID\Attribute\Parameter\GetContainer;
use Ock\DID\Util\AttributesUtil;
use Symfony\Component\DependencyInjection\Reference;

class ParamToServiceArg_Attribute_GetContainer implements ParamToServiceArgInterface {

  /**
   * @inheritDoc
   */
  public function paramGetServiceArg(\ReflectionParameter $parameter): ?Reference {
    return AttributesUtil::hasSingle($parameter, GetContainer::class)
      ? new Reference('container')
      : NULL;
  }

}
