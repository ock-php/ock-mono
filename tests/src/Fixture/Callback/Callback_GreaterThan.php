<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Tests\Fixture\Callback;

/**
 * @obck("greaterThan", "Greater than")
 */
class Callback_GreaterThan {

  /**
   * @var int|float
   */
  private $inf;

  /**
   * @obck("positive", "Number is positive")
   *
   * @return self
   */
  public static function positive(): self {
    return new self(0);
  }

  /**
   * @obck("not_negative", "Number is not negative")
   *
   * @return self
   */
  public static function nonNegative(): self {
    return new self(-1);
  }

  /**
   * Constructor.
   *
   * @param int|float $inf
   */
  public function __construct($inf) {
    $this->inf = $inf;
  }

  /**
   * @param int|float $number
   *
   * @return bool
   */
  public function check($number): bool {
    return $number > $this->inf;
  }

}
