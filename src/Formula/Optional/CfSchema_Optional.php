<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Optional;

use Donquixote\OCUI\Text\TextInterface;

class CfSchema_Optional extends CfSchema_OptionalBase {

  /**
   * @var \Donquixote\OCUI\Text\TextInterface|null
   */
  private $emptySummary;

  /**
   * @var string
   */
  private $emptyPhp = 'NULL';

  /**
   * @param \Donquixote\OCUI\Text\TextInterface $emptySummary
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
   * @return \Donquixote\OCUI\Formula\Optional\CfSchema_Optional
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
