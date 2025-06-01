<?php

declare(strict_types = 1);

namespace Ock\ClassFilesIterator\Tests\Traits;

trait ExceptionTestTrait {

  /**
   * @param class-string $exception_class
   * @param callable(): (void|mixed) $callback
   */
  protected function callAndAssertException(string $exception_class, callable $callback): void {
    // Does not work with non-existing class.
    try {
      $callback();
      $this->fail("Expected exception was not thrown.");
    }
    catch (\Throwable $e) {
      if (get_class($e) !== $exception_class) {
        throw $e;
      }
      $this->addToAssertionCount(1);
    }
  }

}
