<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Tests\Fixture\Callback;

/**
 * @generic:type T
 */
class Callback_Negation {

  /**
   * @var callable
   */
  private $condition;

  /**
   * Constructor.
   *
   * @param callable $condition
   * @signature (T): bool
   */
  public function __construct(callable $condition) {
    $this->condition = $condition;
  }

  /**
   * @param mixed $value
   *
   * @return bool
   */
  public function __invoke($value): bool {
    return !($this->condition)($value);
  }

}
