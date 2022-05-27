<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\SpecificAdapter;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

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
