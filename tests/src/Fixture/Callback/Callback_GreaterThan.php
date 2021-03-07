<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Tests\Fixture\Callback;

/**
 * @ocui("greaterThan", "Greater than")
 */
class Callback_GreaterThan {

  /**
   * @var int|float
   */
  private $inf;

  /**
   * @ocui("positive", "Number is positive")
   *
   * @return self
   */
  public static function positive(): self {
    return new self(0);
  }

  /**
   * @ocui("not_negative", "Number is not negative")
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
