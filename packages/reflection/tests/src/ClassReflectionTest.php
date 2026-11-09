<?php

declare(strict_types = 1);

namespace Ock\Reflection\Tests;

use Ock\Reflection\ClassReflection;
use Ock\Reflection\Tests\Fixtures\ClassWithMissingInterface;
use Ock\Reflection\Tests\Fixtures\ClassWithMissingParent;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(ClassReflection::class)]
class ClassReflectionTest extends TestCase {

  public function testCreateKnown(): void {
    $reflection = ClassReflection::createKnown(\stdClass::class);
    $this->assertEquals(new ClassReflection(\stdClass::class), $reflection);
    $this->assertSame(\stdClass::class, $reflection->name);
  }

  /**
   * Tests a class that can be autoloaded, but has a missing dependency.
   *
   * PHP throws an \Error in this case, which is converted to an exception.
   *
   * A missing trait cannot be tested this way: It causes a fatal error.
   *
   * @param class-string $class
   *   Class with a missing parent or interface.
   * @param string $message
   *   Expected message from the original \Error.
   */
  #[TestWith([ClassWithMissingParent::class, 'Class "Ock\Reflection\Tests\Fixtures\MissingParentClass" not found'])]
  #[TestWith([ClassWithMissingInterface::class, 'Interface "Ock\Reflection\Tests\Fixtures\MissingInterface" not found'])]
  public function testMissingDependency(string $class, string $message): void {
    $this->expectException(\ReflectionException::class);
    $this->expectExceptionMessage("Class $class is not available: $message");
    new ClassReflection($class);
  }

}
