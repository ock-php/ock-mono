<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Optional;

class CfSchema_Optional extends CfSchema_OptionalBase {

  /**
   * @var string|null
   */
  private $emptySummary;

  /**
   * @var mixed|null
   */
  private $emptyValue;

  /**
   * @var string
   */
  private $emptyPhp = 'NULL';

  /**
   * @param string $emptySummary
   *
   * @return static
   */
  public function withEmptySummary($emptySummary) {
    $clone = clone $this;
    $clone->emptySummary = $emptySummary;
    return $clone;
  }

  /**
   * @param mixed $emptyValue
   * @param string|null $emptyPhp
   *
   * @return \Donquixote\Cf\Schema\Optional\CfSchema_Optional
   */
  public function withEmptyValue($emptyValue, $emptyPhp = NULL): CfSchema_Optional {

    $clone = clone $this;
    $clone->emptyValue = $emptyValue;
    $clone->emptyPhp = $emptyPhp
      ?? var_export($emptyValue, true);

    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmptySummary(): ?string {
    return $this->emptySummary;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmptyValue() {
    return $this->emptyValue;
  }

  /**
   * {@inheritdoc}
   */
  public function getEmptyPhp(): string {
    return $this->emptyPhp;
  }
}
