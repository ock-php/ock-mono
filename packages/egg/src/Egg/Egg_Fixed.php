<?php

declare(strict_types = 1);

namespace Ock\Egg\Egg;

use Psr\Container\ContainerInterface;

class Egg_Fixed implements EggInterface {

  /**
   * Constructor.
   *
   * @param mixed $value
   */
  public function __construct(
    private readonly mixed $value,
  ) {}

  public function containerGetValue(ContainerInterface $container): mixed {
    return $this->value;
  }

}
