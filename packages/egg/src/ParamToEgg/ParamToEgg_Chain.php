<?php

declare(strict_types = 1);

namespace Ock\Egg\ParamToEgg;

use Ock\Egg\Egg\EggInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[AsAlias(public: true)]
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
    static $recursion_depth = 0;
    if ($recursion_depth > 5) {
      throw new \RuntimeException('Seems like infinite loop.');
    }
    foreach ($this->paramToEggs as $paramToEgg) {
      ++$recursion_depth;
      try {
        $egg = $paramToEgg->paramGetEgg($parameter);
      }
      finally {
        --$recursion_depth;
      }
      if ($egg !== NULL) {
        return $egg;
      }
    }
    return NULL;
  }

}
