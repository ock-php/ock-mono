<?php

declare(strict_types = 1);

namespace Ock\Egg\Egg;

use Donquixote\DID\Exception\ContainerToValueException;
use Psr\Container\ContainerInterface;

/**
 * @template T
 *
 * @template-extends Egg_ArgumentsBase<T>
 */
class Egg_Construct extends Egg_ArgumentsBase {

  /**
   * Constructor.
   *
   * @param class-string<T> $class
   * @param (mixed|\Ock\Egg\Egg\EggInterface)[] $args
   */
  public function __construct(
    private readonly string $class,
    array $args,
  ) {
    parent::__construct($args);
  }

  /**
   * {@inheritdoc}
   */
  protected function getWithArgs(ContainerInterface $container, array $args): mixed {
    try {
      return new ($this->class)(...$args);
    }
    catch (\Throwable $e) {
      throw new ContainerToValueException($e->getMessage(), 0, $e);
    }
  }

}
