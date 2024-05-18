<?php

declare(strict_types = 1);

namespace Ock\Egg\ParamToEgg;

use Ock\Egg\Egg\EggInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[AsAlias]
class ParamToEgg_Chain implements ParamToEggInterface {

  /**
   * Constructor.
   *
   * @param \Ock\Egg\ParamToEgg\ParamToEggInterface[] $p2CTVs
   */
  public function __construct(
    #[AutowireIterator(ParamToEggInterface::SERVICE_TAG)]
    private readonly iterable $p2CTVs,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?EggInterface {
    foreach ($this->p2CTVs as $paramToCTV) {
      $egg = $paramToCTV->paramGetCTV($parameter);
      if ($egg !== NULL) {
        return $egg;
      }
    }
    return NULL;
  }

}
