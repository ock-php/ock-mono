<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\ClassNamesIA;

/**
 * @template TKey
 *
 * @template-implements ClassNamesIAInterface<TKey>
 */
class ClassNamesIA_Array implements ClassNamesIAInterface {

  /**
   * Constructor.
   *
   * @param array<TKey, class-string> $values
   */
  public function __construct(
    private readonly array $values,
  ) {
    // Validate input if assertions are enabled.
    assert(!array_filter(
      $values,
      fn ($value) => !is_string($value),
    ), 'Array values must be class names.');
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    yield from $this->values;
  }

}
