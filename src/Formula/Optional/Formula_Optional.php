<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Optional;

use Donquixote\ObCK\Text\TextInterface;

class Formula_Optional extends Formula_OptionalBase {

  /**
   * @var \Donquixote\ObCK\Text\TextInterface|null
   */
  private $emptySummary;

  /**
   * @var string
   */
  private $emptyPhp = 'NULL';

  /**
   * @param \Donquixote\ObCK\Text\TextInterface $emptySummary
   *
   * @return static
   */
  public function withEmptySummary(TextInterface $emptySummary) {
    $clone = clone $this;
    $clone->emptySummary = $emptySummary;
    return $clone;
  }

  /**
   * @param string $emptyPhp
   *
   * @return \Donquixote\ObCK\Formula\Optional\Formula_Optional
   */
  public function withEmptyPhp(string $emptyPhp): Formula_Optional {
    $clone = clone $this;
    $clone->emptyPhp = $emptyPhp;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmptySummary(): ?TextInterface {
    return $this->emptySummary;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmptyPhp(): string {
    return $this->emptyPhp;
  }
}
