<?php
declare(strict_types=1);

namespace Ock\Adaptism\SpecificAdapter;

use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;

/**
 * A specific adapter can adapt some objects to some target types.
 */
interface SpecificAdapterInterface {

  /**
   * Finds an adapter for an adaptee object.
   *
   * @template TResult of object
   *
   * @param object $adaptee
   * @param class-string<TResult> $resultType
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return object|null
   *   An instance of $interface, or NULL if not found.
   *
   * @phpstan-return TResult|null
   * @psalm-return TResult|null
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
   */
  public function adapt(
    object $adaptee,
    string $resultType,
    UniversalAdapterInterface $universalAdapter,
  ): ?object;

}
