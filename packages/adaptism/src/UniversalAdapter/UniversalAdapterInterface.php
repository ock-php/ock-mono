<?php
declare(strict_types=1);

namespace Ock\Adaptism\UniversalAdapter;

interface UniversalAdapterInterface {

  /**
   * @template TResult of object
   *
   * @param object $adaptee
   * @param class-string<TResult> $resultType
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface|null $universalAdapter
   *   Top-level universal adapter, or NULL to use the object itself.
   *
   * @return object|null
   *   An instance of $destinationInterface, or
   *   NULL, if adaption is not supported for the given types.
   *
   * @phpstan-return TResult|null
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  public function adapt(
    object $adaptee,
    string $resultType,
    UniversalAdapterInterface $universalAdapter = null,
  ): ?object;

}
