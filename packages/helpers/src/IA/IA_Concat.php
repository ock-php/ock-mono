<?php

declare(strict_types=1);

namespace Donquixote\Helpers\IA;

/**
 * Iterator aggregate based on an array.
 *
 * @template TPart of \IteratorAggregate
 */
class IA_Concat implements \IteratorAggregate {

  /**
   * Constructor.
   *
   * @param TPart[] $parts
   */
  public function __construct(
    protected readonly array $parts,
  ) {
    // Validate, if assertions are enabled.
    assert([] === array_filter(
      $this->parts,
        fn ($part) => !static::partIsValid($part),
    ));
  }

  /**
   * Creates a new instance from a list of candidate objects.
   *
   * @param iterable $candidates
   *   List of objects that may or may not be suitable parts.
   *   This accepts any iterable, to support symfony tagged services.
   *
   * @return static
   *   New instance.
   */
  public static function fromCandidateObjects(iterable $candidates): static {
    $parts = [];
    foreach ($candidates as $candidate) {
      if (static::partIsValid($candidate)) {
        $parts[] = $candidate;
      }
    }
    return new static($parts);
  }

  /**
   * Checks whether a sub-iterator is valid for this listing.
   *
   * @param mixed $part
   *
   * @return bool
   */
  protected static function partIsValid(mixed $part): bool {
    return $part instanceof \IteratorAggregate;
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    foreach ($this->parts as $part) {
      yield from $part;
    }
  }

}
