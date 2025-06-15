<?php

declare(strict_types = 1);

namespace Drupal\Tests\controller_attributes\Traits;

trait ExceptionTestTrait {

  /**
   * @template T of \Throwable
   *
   * @param class-string<T>|(T&\Throwable) $exception
   *   Expected exception or exception class.
   * @param callable(): (void|mixed) $callback
   *   Callback that is expected to throw an exception.
   *
   * @return T&\Throwable
   *   The caught exception, for further assertions.
   */
  protected function callAndAssertException(string|\Throwable $exception, callable $callback): \Throwable {
    // Does not work with non-existing class.
    try {
      $callback();
      $this->fail("Expected exception was not thrown.");
    }
    catch (\Throwable $e) {
      $expected_exception_class = is_string($exception) ? $exception : get_class($exception);
      if (is_string($exception) && get_class($e) !== $exception) {
        throw $e;
      }
      if (is_object($exception) && get_class($e) !== get_class($exception)) {
        throw $e;
      }
      if (is_object($exception)) {
        $this->assertEquals($exception, $e);
      }
      $this->addToAssertionCount(1);
      return $e;
    }
  }

}

