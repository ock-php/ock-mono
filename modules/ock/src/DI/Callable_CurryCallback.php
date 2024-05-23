<?php

declare(strict_types = 1);

namespace Drupal\ock\DI;

class Callable_CurryCallback implements CallableInterface {

  /**
   * Constructor.
   *
   * @param callable $callback
   * @param array $args
   */
  public function __construct(
    private readonly mixed $callback,
    private readonly array $args,
  ) {}

  /**
   * @param mixed ...$args
   *
   * @return object
   */
  public function __invoke(...$args): object {
    return ($this->callback)(...$this->args, ...$args);
  }

}
