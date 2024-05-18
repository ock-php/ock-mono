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
   * @param \Ock\Egg\ParamToEgg\ParamToEggInterface[] $paramToEggs
   */
  public function __construct(
    #[AutowireIterator(ParamToEggInterface::SERVICE_TAG)]
    private readonly iterable $paramToEggs,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function paramGetCTV(\ReflectionParameter $parameter): ?EggInterface {
    foreach ($this->paramToEggs as $paramToEgg) {
      $egg = $paramToEgg->paramGetCTV($parameter);
      if ($egg !== NULL) {
        return $egg;
      }
    }
    return NULL;
  }

}
