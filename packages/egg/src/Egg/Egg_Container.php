<?php

declare(strict_types = 1);

namespace Ock\Egg\Egg;

use Psr\Container\ContainerInterface;

class Egg_Container implements EggInterface {

  /**
   * {@inheritdoc}
   */
  public function hatch(ContainerInterface $container): ContainerInterface {
    return $container;
  }

}
