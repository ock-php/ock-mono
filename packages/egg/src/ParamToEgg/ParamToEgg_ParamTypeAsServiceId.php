<?php

declare(strict_types = 1);

namespace Ock\Egg\ParamToEgg;

use Ock\Egg\Egg\Egg_ServiceId;
use Ock\Egg\Egg\EggInterface;
use Psr\Container\ContainerInterface;

class ParamToEgg_ParamTypeAsServiceId implements ParamToEggInterface {

  public function __construct(
    private readonly ContainerInterface $container,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function paramGetEgg(\ReflectionParameter $parameter): ?EggInterface {
    $type = $parameter->getType();
    if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
      return null;
    }
    $name = $type->getName();
    if (!$this->container->has($name)) {
      return null;
    }
    return new Egg_ServiceId($name);
  }

}
