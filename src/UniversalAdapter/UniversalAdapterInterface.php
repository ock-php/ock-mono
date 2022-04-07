<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\UniversalAdapter;

interface UniversalAdapterInterface {

  /**
   * @template T as object
   *
   * @param object $original
   * @param class-string<T> $resultType
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface|null $universalAdapter
   *
   * @return T|null
   *   An instance of $destinationInterface, or
   *   NULL, if adaption is not supported for the given types.
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public function adapt(
    object $original,
    string $resultType,
    UniversalAdapterInterface $universalAdapter = null,
  ): ?object;

}
