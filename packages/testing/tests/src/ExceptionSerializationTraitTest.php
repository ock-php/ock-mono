<?php

declare(strict_types=1);

namespace Ock\Testing\Tests;

use Ock\Testing\ExceptionCleaner;
use Ock\Testing\ExceptionSerializationTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\TestCase;

#[CoversTrait(ExceptionSerializationTrait::class)]
#[CoversClass(ExceptionCleaner::class)]
class ExceptionSerializationTraitTest extends TestCase {

  private string|false $exceptionIgnoreArgs = false;

  protected function setUp(): void {
    parent::setUp();
    // Make sure that call arguments end up in the exception backtrace.
    $this->exceptionIgnoreArgs = ini_set('zend.exception_ignore_args', '0');
  }

  protected function tearDown(): void {
    if ($this->exceptionIgnoreArgs !== false) {
      ini_set('zend.exception_ignore_args', $this->exceptionIgnoreArgs);
    }
    parent::tearDown();
  }

  public function testOnNotSuccessfulTest(): void {
    $exception = $this->createUnserializableException();
    $serialization_error = null;
    try {
      serialize($exception);
    }
    catch (\Exception $e) {
      $serialization_error = $e->getMessage();
    }
    $this->assertMatchesRegularExpression(
      "/^Serialization of '\\w+' is not allowed$/",
      (string) $serialization_error,
    );

    // This happens in a test that uses the trait, when the test fails.
    // Only the exception is serialized, not the test case object, so an
    // anonymous class is fine here.
    $test_case = new class('testDummy') extends TestCase {

      use ExceptionSerializationTrait;

      public function callOnNotSuccessfulTest(\Throwable $t): never {
        $this->onNotSuccessfulTest($t);
      }

    };
    try {
      $test_case->callOnNotSuccessfulTest($exception);
    }
    catch (\Throwable $rethrown) {
      $this->assertSame($exception, $rethrown);
    }

    // This happens in process isolation, when the result is passed back.
    $unserialized = unserialize(serialize($exception));
    $this->assertInstanceOf(\RuntimeException::class, $unserialized);
    $this->assertSame('Outer exception.', $unserialized->getMessage());
    $previous = $unserialized->getPrevious();
    $this->assertInstanceOf(\LogicException::class, $previous);
    $this->assertSame('Inner exception.', $previous->getMessage());

    // Closures and reflectors in the trace are replaced with strings.
    $args = array_merge(...array_map(
      fn (array $frame) => $frame['args'] ?? [],
      $previous->getTrace(),
    ));
    $this->assertContains('{ReflectionClass}', $args);
    $this->assertNotEmpty(preg_grep('@^\{closure: .*:\d+}$@', array_filter($args, is_string(...))));
  }

  /**
   * Creates an exception with closures and reflectors in the backtrace.
   *
   * @return \RuntimeException
   *   Exception with a previous exception.
   */
  private function createUnserializableException(): \RuntimeException {
    try {
      $this->throwWithArgs(fn () => null, new \ReflectionClass(self::class));
    }
    catch (\LogicException $e) {
      return new \RuntimeException('Outer exception.', 0, $e);
    }
  }

  /**
   * Throws an exception, with the given arguments in the backtrace.
   *
   * @param \Closure $closure
   *   A closure, which cannot be serialized.
   * @param \ReflectionClass<self> $reflector
   *   A reflector, which cannot be serialized.
   *
   * @return never
   *   Always throws.
   */
  private function throwWithArgs(\Closure $closure, \ReflectionClass $reflector): never {
    throw new \LogicException('Inner exception.');
  }

}
