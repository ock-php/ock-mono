<?php

declare(strict_types = 1);

namespace Drupal\ock\DI;

class Callable_CurryConstruct implements CallableInterface {

  /**
   * Constructor.
   *
   * @param class-string $class
   * @param array $args
   */
  public function __construct(
    private readonly string $class,
    private readonly array $args,
  ) {}

  /**
   * @param mixed ...$args
   *
   * @return object
   */
  public function __invoke(...$args): object {
    return new ($this->class)(...array_replace($this->args, $args));
  }

}
