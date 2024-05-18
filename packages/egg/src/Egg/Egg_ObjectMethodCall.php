<?php

declare(strict_types = 1);

namespace Ock\Egg\Egg;

use Psr\Container\ContainerInterface;

/**
 * Treats the service itself as a callable.
 */
class Egg_ObjectMethodCall extends Egg_ArgumentsBase {

  /**
   * Constructor.
   *
   * @param \Ock\Egg\Egg\EggInterface $objectEgg
   * @param string $method
   * @param (mixed|\Ock\Egg\Egg\EggInterface)[] $args
   */
  public function __construct(
    private readonly EggInterface $objectEgg,
    private readonly string $method,
    array $args,
  ) {
    parent::__construct($args);
  }

  /**
   * {@inheritdoc}
   */
  protected function getWithArgs(ContainerInterface $container, array $args): mixed {
    $object = $this->objectEgg->hatch($container);
    return $object->{$this->method}(...$args);
  }

}
