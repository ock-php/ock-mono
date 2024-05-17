<?php

declare(strict_types = 1);

namespace Ock\Egg\Egg;

use Psr\Container\ContainerInterface;

/**
 * @template T
 */
interface EggInterface {

  /**
   * @param \Psr\Container\ContainerInterface $container
   *
   * @return T
   *
   * @throws \Donquixote\DID\Exception\ContainerToValueException
   */
  public function containerGetValue(ContainerInterface $container): mixed;

}
