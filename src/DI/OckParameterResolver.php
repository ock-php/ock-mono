<?php

namespace Drupal\ock\DI;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Adaptism\DI\ServiceIdHavingInterface;
use Donquixote\Adaptism\Util\AttributesUtil;
use Donquixote\Adaptism\Util\ReflectionTypeUtil;
use Donquixote\Adaptism\Util\ServiceAttributesUtil;
use Drupal\ock\Attribute\DI\DependencyInjectionArgumentInterface;
use Drupal\ock\Attribute\DI\RegisterService;
use Psr\Container\ContainerInterface;

/**
 * Implements the class resolver interface supporting class names and services.
 */
#[RegisterService]
class OckParameterResolver {

  private array $namedArgs = [];

  private array $typeArgs = [];

  public function __construct(
    #[GetService('service_container')]
    private readonly ContainerInterface $container,
  ) {}

  public function withNamedArgs(array $args): static {
    $clone = clone $this;
    $clone->namedArgs = $args;
    return $clone;
  }

  public function withTypeArgs(array $args): static {
    $clone = clone $this;
    $clone->typeArgs = $args;
    return $clone;
  }

  /**
   * @param \ReflectionParameter $parameter
   *
   * @return mixed
   *
   * @throws \Exception
   *   Cannot resolve parameter.
   */
  public function resolveParameter(\ReflectionParameter $parameter): mixed {
    if ($this->namedArgs) {
      $name = $parameter->getName();
      if (array_key_exists($name, $this->namedArgs)) {
        return $this->namedArgs[$name];
      }
    }
    if ($this->typeArgs) {
      $rType = $parameter->getType();
      if ($rType instanceof \ReflectionNamedType) {
        $typeName = $rType->getName();
        if (array_key_exists($typeName, $this->typeArgs)) {
          return $this->typeArgs[$typeName];
        }
      }
    }
    if (NULL !== $instance = AttributesUtil::getSingle($parameter, DependencyInjectionArgumentInterface::class)) {
      return $instance->paramGetValue($parameter, $this->container);
    }
    $id = ServiceAttributesUtil::paramRequireServiceId($parameter);
    return $this->container->get($id);
  }

}
