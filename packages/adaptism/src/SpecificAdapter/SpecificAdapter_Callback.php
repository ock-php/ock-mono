<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Egg\Egg\Egg_Construct;
use Ock\Egg\Egg\EggInterface;

class SpecificAdapter_Callback implements SpecificAdapterInterface {

  /**
   * @var callable
   */
  private readonly mixed $callback;

  /**
   * Constructor.
   *
   * @param callable $callback
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param list<mixed> $moreArgs
   */
  public function __construct(
    callable $callback,
    private readonly bool $hasResultTypeParameter,
    private readonly bool $hasUniversalAdapterParameter,
    private readonly array $moreArgs,
  ) {
    $this->callback = $callback;
  }

  /**
   * @param \Ock\Egg\Egg\EggInterface|callable $callbackOrEgg
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param list<\Ock\Egg\Egg\EggInterface|mixed> $moreArgEggs
   *
   * @return \Ock\Egg\Egg\EggInterface<self>
   */
  public static function ctv(
    EggInterface|callable $callbackOrEgg,
    bool $hasResultTypeParameter,
    bool $hasUniversalAdapterParameter,
    array $moreArgEggs,
  ): EggInterface {
    return new Egg_Construct(self::class, [
      $callbackOrEgg,
      $hasResultTypeParameter,
      $hasUniversalAdapterParameter,
      $moreArgEggs,
    ]);
  }

  /**
   * @param class-string|\Ock\Egg\Egg\EggInterface $classOrEgg
   * @param string $method
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param array $moreArgsEggs
   *
   * @return \Ock\Egg\Egg\EggInterface<self>
   */
  public static function ctvMethodCall(
    string|EggInterface $classOrEgg,
    string $method,
    bool $hasResultTypeParameter,
    bool $hasUniversalAdapterParameter,
    array $moreArgsEggs,
  ): EggInterface {
    return new Egg_Construct(self::class, [
      [$classOrEgg, $method],
      $hasResultTypeParameter,
      $hasUniversalAdapterParameter,
      $moreArgsEggs,
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function adapt(
    object $adaptee,
    string $resultType,
    UniversalAdapterInterface $universalAdapter,
  ): ?object {
    $args = [$adaptee];
    if ($this->hasResultTypeParameter) {
      $args[] = $resultType;
    }
    if ($this->hasUniversalAdapterParameter) {
      $args[] = $universalAdapter;
    }
    return ($this->callback)(...$args, ...$this->moreArgs);
  }

}
