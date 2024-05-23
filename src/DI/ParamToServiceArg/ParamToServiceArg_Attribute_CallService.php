<?php

declare(strict_types = 1);

namespace Drupal\ock\DI\ParamToServiceArg;

use Donquixote\DID\Util\AttributesUtil;
use Donquixote\DID\Attribute\Parameter\CallService;
use Drupal\ock\DI\ContainerArgumentExpression;
use Drupal\ock\DI\ContainerExpressionUtil;
use Symfony\Component\DependencyInjection\Reference;

class ParamToServiceArg_Attribute_CallService implements ParamToServiceArgInterface {

  /**
   * @inheritDoc
   */
  public function paramGetServiceArg(\ReflectionParameter $parameter): ?ContainerArgumentExpression {
    /** @var \Donquixote\DID\Attribute\Parameter\CallService $attribute */
    $attribute = AttributesUtil::getSingle($parameter, CallService::class);
    if ($attribute === NULL) {
      return NULL;
    }
    $id = $attribute->paramGetServiceId($parameter);
    return new ContainerArgumentExpression(
      ContainerExpressionUtil::OP_CALL,
      [
        'object' => new Reference($id),
        'args' => $attribute->args,
      ],
    );
  }

}
