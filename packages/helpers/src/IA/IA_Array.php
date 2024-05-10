<?php

declare(strict_types=1);

namespace Donquixote\Helpers\IA;

/**
 * Iterator aggregate based on an array.
 *
 * @template-covariant TKey
 * @template-covariant TValue
 *
 * @template-implements \IteratorAggregate<TKey, TValue>
 */
class IA_Array implements \IteratorAggregate {

  /**
   * Constructor.
   *
   * @param array<TKey, TValue> $values
   */
  public function __construct(
    private readonly array $values,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    yield from $this->values;
  }

}
