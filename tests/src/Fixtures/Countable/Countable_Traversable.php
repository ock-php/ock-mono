<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Countable;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;

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
