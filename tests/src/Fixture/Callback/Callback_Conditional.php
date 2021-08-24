<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Tests\Fixture\Callback;

/**
 * @generic:type T
 */
class Callback_Conditional {

  /**
   * @var callable
   */
  private $condition;

  /**
   * @var callable
   */
  private $callbackIfTrue;

  /**
   * @var callable
   */
  private $callbackIfFalse;

  /**
   * Constructor.
   *
   * @param callable $condition
   * @signature (T): bool
   * @param callable $callbackIfTrue
   * @signature (T): S
   * @param callable $callbackIfFalse
   * @signature (T): S
   */
  public function __construct(
    callable $condition,
    callable $callbackIfTrue,
    callable $callbackIfFalse
  ) {
    $this->condition = $condition;
    $this->callbackIfTrue = $callbackIfTrue;
    $this->callbackIfFalse = $callbackIfFalse;
  }

  /**
   * @param mixed $value
   *
   * @return mixed
   */
  public function __invoke($value) {
    return ($this->condition)($value)
      ? ($this->callbackIfTrue)($value)
      : ($this->callbackIfFalse)($value);
  }

}
