<?php

declare(strict_types = 1);

namespace Ock\Egg\Egg;

use Ock\Egg\Exception\EggHatchException;
use Psr\Container\ContainerInterface;

class Egg_CallableCall extends Egg_ArgumentsBase {

  /**
   * Constructor.
   *
   * @param \Ock\Egg\Egg\EggInterface $callbackEgg
   * @param (mixed|\Ock\Egg\Egg\EggInterface)[] $args
   */
  public function __construct(
    private readonly EggInterface $callbackEgg,
    array $args,
  ) {
    parent::__construct($args);
  }

  /**
   * @param callable $callback
   * @param (mixed|\Ock\Egg\Egg\EggInterface)[] $args
   *
   * @return self
   */
  public static function createFixed(callable $callback, array $args): self {
    return new self(
      new Egg_Fixed($callback),
      $args,
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getWithArgs(ContainerInterface $container, array $args): mixed {
    $callback = $this->callbackEgg->hatch($container);
    try {
      return $callback(...$args);
    }
    catch (\Throwable $e) {
      throw new EggHatchException($e->getMessage(), 0, $e);
    }
  }

}
