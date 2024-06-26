<?php

declare(strict_types = 1);

namespace Ock\DID\Callback;

use Ock\Egg\Egg\Egg_Construct;
use Ock\Egg\Egg\EggInterface;

/**
 * @template T of object
 */
class CurryConstruct {

  /**
   * Constructor.
   *
   * @param class-string<T> $class
   * @param list<mixed> $args
   * @param array<int, int> $curryArgNames
   * @param array<int, callable> $callableArgs
   */
  public function __construct(
    private readonly string $class,
    private readonly array $args,
    private readonly array $curryArgNames = [],
    private readonly array $callableArgs = [],
  ) {}

  /**
   * Creates an egg for this class.
   *
   * @template TT as object
   *
   * @param class-string<TT> $class
   * @param list<\Ock\Egg\Egg\EggInterface|mixed> $namedArgEggs
   * @param list<string> $curryArgNames
   * @param array $callableArgEggs
   *
   * @return \Ock\Egg\Egg\EggInterface<self<TT>>
   */
  public static function egg(
    string $class,
    array $namedArgEggs,
    array $curryArgNames = [],
    array $callableArgEggs = [],
  ): EggInterface {
    return new Egg_Construct(self::class, [
      $class,
      $namedArgEggs,
      $curryArgNames,
      $callableArgEggs,
    ]);
  }

  /**
   * The method that makes this a callable.
   *
   * @param mixed ...$args
   *
   * @return T&object
   */
  public function __invoke(mixed ...$args): object {
    $constructorArgs = $this->args;
    foreach ($this->curryArgNames as $position => $name) {
      $constructorArgs[$name] = $args[$position];
    }
    foreach ($this->callableArgs as $name => $callable) {
      $constructorArgs[$name] = $callable(...$args);
    }
    return new ($this->class)(...$constructorArgs);
  }

}
