<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\FactsIA;

use function Ock\Helpers\array_filter_instanceof;

/**
 * Finds results in reflection classes.
 *
 * This can be used instead of FactoryDiscovery, to allow to decorate the
 * inspectors on class level.
 *
 * @template TFactKey
 * @template TFact
 *
 * @template-implements FactsIAInterface<TFactKey, TFact>
 */
class FactsIA_Concat implements FactsIAInterface {

  /**
   * Constructor.
   *
   * @param list<\Ock\ClassDiscovery\FactsIA\FactsIAInterface<TFactKey, TFact>> $factsIAs
   */
  public function __construct(
    private readonly array $factsIAs,
  ) {}

  /**
   * Creates a new instance from a list of candidate objects.
   *
   * @param iterable<object> $candidates
   *   Sequence of objects which may contain inspectors and class lists.
   *   This accepts any iterable, to support symfony tagged services.
   *
   * @return self<mixed, mixed>
   *   New instance.
   */
  public static function fromCandidateObjects(iterable $candidates): self {
    $candidates = \iterator_to_array($candidates, false);
    $factsIAs = array_filter_instanceof($candidates, FactsIAInterface::class);
    return new self($factsIAs);
  }

  /**
   * @return \Ock\ClassDiscovery\FactsIA\FactsIAInterface<TFactKey, TFact>
   */
  public function optimize(): FactsIAInterface {
    return count($this->factsIAs) === 1
      ? $this->factsIAs[array_key_first($this->factsIAs)]
      : $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    foreach ($this->factsIAs as $factsIA) {
      yield from $factsIA;
    }
  }

}
