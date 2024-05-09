<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

interface SpecificAdapterInterface {

  /**
   * @template T of object
   *
   * @param object $adaptee
   * @param class-string<T> $resultType
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return object|null
   *   An instance of $interface, or NULL if not found.
   *
   * @phpstan-return T|null
   * @psalm-return T|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public function adapt(
    object $adaptee,
    string $resultType,
    UniversalAdapterInterface $universalAdapter,
  ): ?object;

}
