<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

interface SpecificAdapterInterface {

  /**
   * @template T as object
   *
   * @param object $adaptee
   * @param class-string<T> $resultType
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return object|null
   *   An instance of $interface, or NULL if not found.
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public function adapt(
    object $adaptee,
    string $resultType,
    UniversalAdapterInterface $universalAdapter,
  ): ?object;

}
