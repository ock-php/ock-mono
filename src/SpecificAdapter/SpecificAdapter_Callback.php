<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\DID\ContainerToValue\ContainerToValue_Construct;
use Donquixote\DID\ContainerToValue\ContainerToValueInterface;

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
   * @param \Donquixote\DID\ContainerToValue\ContainerToValueInterface|callable $callbackCTV
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param list<\Donquixote\DID\ContainerToValue\ContainerToValueInterface|mixed> $moreArgCTVs
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface<self>
   */
  public static function ctv(
    ContainerToValueInterface|callable $callbackCTV,
    bool $hasResultTypeParameter,
    bool $hasUniversalAdapterParameter,
    array $moreArgCTVs,
  ): ContainerToValueInterface {
    return new ContainerToValue_Construct(self::class, [
      $callbackCTV,
      $hasResultTypeParameter,
      $hasUniversalAdapterParameter,
      $moreArgCTVs,
    ]);
  }

  /**
   * @param class-string|\Donquixote\DID\ContainerToValue\ContainerToValueInterface $classOrObjectCTV
   * @param string $method
   * @param bool $hasResultTypeParameter
   * @param bool $hasUniversalAdapterParameter
   * @param array $moreArgsCTVs
   *
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface<self>
   */
  public static function ctvMethodCall(
    string|ContainerToValueInterface $classOrObjectCTV,
    string $method,
    bool $hasResultTypeParameter,
    bool $hasUniversalAdapterParameter,
    array $moreArgsCTVs,
  ): ContainerToValueInterface {
    return new ContainerToValue_Construct(self::class, [
      [$classOrObjectCTV, $method],
      $hasResultTypeParameter,
      $hasUniversalAdapterParameter,
      $moreArgsCTVs,
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
