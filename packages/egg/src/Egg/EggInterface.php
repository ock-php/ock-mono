<?php

declare(strict_types = 1);

namespace Ock\Egg\Egg;

use Psr\Container\ContainerInterface;

/**
 * An egg can produce an object or value given a container.
 *
 * It is serializable by convention.
 *
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
