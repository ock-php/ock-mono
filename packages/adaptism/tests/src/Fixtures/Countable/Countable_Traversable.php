<?php
declare(strict_types=1);

namespace Ock\Adaptism\Tests\Fixtures\Countable;

use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\Attribute\Parameter\Adaptee;

#[Adapter]
class Countable_Traversable implements \Countable {

  /**
   * @param \Traversable $iterator
   */
  public function __construct(
    #[Adaptee] private readonly \Traversable $iterator,
  ) {}

  /**
   * @return int
   */
  public function count(): int {
    return \iterator_count($this->iterator);
  }
}
