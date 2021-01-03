<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Optional;

use Donquixote\Cf\Text\TextInterface;

class CfSchema_Optional extends CfSchema_OptionalBase {

  /**
   * @var \Donquixote\Cf\Text\TextInterface|null
   */
  private $emptySummary;

  /**
   * @var string
   */
  private $emptyPhp = 'NULL';

  /**
   * @param \Donquixote\Cf\Text\TextInterface $emptySummary
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
   * @return \Donquixote\Cf\Schema\Optional\CfSchema_Optional
   */
  public function withEmptyPhp(string $emptyPhp): CfSchema_Optional {
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
