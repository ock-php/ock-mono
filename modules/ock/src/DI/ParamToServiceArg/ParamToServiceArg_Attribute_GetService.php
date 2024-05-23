<?php

declare(strict_types = 1);

namespace Drupal\ock\DI\ParamToServiceArg;

use Ock\DID\Attribute\Parameter\GetServiceInterface;
use Ock\DID\Util\AttributesUtil;
use Symfony\Component\DependencyInjection\Reference;

class ParamToServiceArg_Attribute_GetService implements ParamToServiceArgInterface {

  /**
   * @inheritDoc
   */
  public function paramGetServiceArg(\ReflectionParameter $parameter): ?Reference {
    $attribute = AttributesUtil::getSingle($parameter, GetServiceInterface::class);
    if ($attribute === NULL) {
      return NULL;
    }
    $id = $attribute->paramGetServiceId($parameter);
    return new Reference($id);
  }

}
