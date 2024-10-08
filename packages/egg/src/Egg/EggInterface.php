<?php

declare(strict_types = 1);

namespace Ock\Egg\Egg;

use Psr\Container\ContainerInterface;

/**
 * An egg can produce an object or value given a container.
 *
 * It is serializable by convention.
 *
 * @template-covariant T
 */
interface EggInterface {

  /**
   * @param \Psr\Container\ContainerInterface $container
   *
   * @return T
   *
   * @throws \Psr\Container\ContainerExceptionInterface
   */
  public function hatch(ContainerInterface $container): mixed;

}
