<?php

declare(strict_types = 1);

namespace Drupal\ock\DI\ParamToServiceArg;

use Drupal\ock\DI\ContainerArgumentExpression;
use Drupal\ock\DI\ContainerExpressionUtil;
use Ock\DID\Attribute\Parameter\CallServiceMethod;
use Ock\DID\Util\AttributesUtil;
use Symfony\Component\DependencyInjection\Reference;

class ParamToServiceArg_Attribute_CallServiceMethod implements ParamToServiceArgInterface {

  /**
   * @inheritDoc
   */
  public function paramGetServiceArg(\ReflectionParameter $parameter): ?ContainerArgumentExpression {
    /** @var \Ock\DID\Attribute\Parameter\CallServiceMethod $attribute */
    $attribute = AttributesUtil::getSingle($parameter, CallServiceMethod::class);
    if ($attribute === NULL) {
      return NULL;
    }
    return new ContainerArgumentExpression(
      ContainerExpressionUtil::OP_METHOD,
      [
        'object' => new Reference($attribute->serviceId),
        'method' => $attribute->method,
        'args' => $attribute->args,
      ],
    );
  }

}
