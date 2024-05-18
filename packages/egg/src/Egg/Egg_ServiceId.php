<?php

declare(strict_types = 1);

namespace Ock\Egg\Egg;

use Psr\Container\ContainerInterface;

class Egg_ServiceId implements EggInterface {

  /**
   * Constructor.
   *
   * @param string $serviceId
   */
  public function __construct(
    private readonly string $serviceId,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function hatch(ContainerInterface $container): mixed {
    return $container->get($this->serviceId);
  }

}
