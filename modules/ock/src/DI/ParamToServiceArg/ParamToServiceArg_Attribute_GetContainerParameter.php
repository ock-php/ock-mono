<?php

declare(strict_types = 1);

namespace Drupal\ock\DI\ParamToServiceArg;

use Ock\DID\Util\AttributesUtil;
use Drupal\ock\Attribute\DI\GetContainerParameter;

class ParamToServiceArg_Attribute_GetContainerParameter implements ParamToServiceArgInterface {

  /**
   * @inheritDoc
   */
  public function paramGetServiceArg(\ReflectionParameter $parameter): ?string {
    /** @var \Drupal\ock\Attribute\DI\GetContainerParameter $attribute */
    $attribute = AttributesUtil::getSingle($parameter, GetContainerParameter::class);
    if ($attribute === NULL) {
      return NULL;
    }
    return '%' . $attribute->name . '%';
  }

}
