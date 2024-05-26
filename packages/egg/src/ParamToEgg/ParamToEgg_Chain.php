<?php

declare(strict_types = 1);

namespace Ock\Egg\ParamToEgg;

use Ock\DID\Attribute\Service;
use Ock\Egg\Egg\EggInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[AsAlias]
#[Service]
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
  public function paramGetEgg(\ReflectionParameter $parameter): ?EggInterface {
    foreach ($this->paramToEggs as $paramToEgg) {
      $egg = $paramToEgg->paramGetEgg($parameter);
      if ($egg !== NULL) {
        return $egg;
      }
    }
    return NULL;
  }

}
