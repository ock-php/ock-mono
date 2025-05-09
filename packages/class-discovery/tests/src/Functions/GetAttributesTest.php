<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\Tests\Functions;

use Ock\ClassDiscovery\Tests\Fixtures\Attribute\OtherTestAttribute;
use Ock\ClassDiscovery\Tests\Fixtures\Attribute\ReflectorAwareTestAttribute;
use Ock\ClassDiscovery\Tests\Fixtures\Attribute\TestAttribute;
use Ock\ClassDiscovery\Tests\Fixtures\Attribute\TestAttributeInterface;
use Ock\ClassDiscovery\Tests\Fixtures\TestClassWithAttributes;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use function Ock\ClassDiscovery\get_attributes;
use function Ock\ClassDiscovery\get_raw_attributes;

/**
 * @covers \Ock\ClassDiscovery\get_attributes()
 * @covers \Ock\ClassDiscovery\get_raw_attributes()
 */
class GetAttributesTest extends TestCase {

  /**
   * @param \Closure(\ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant, class-string, int=): list<object> $get_attributes
   */
  #[DataProvider('providerGetAttributesFunction')]
  public function testOnClass(\Closure $get_attributes): void {
    $reflector = new \ReflectionClass(TestClassWithAttributes::class);
    $attributes = $get_attributes($reflector, TestAttribute::class);
    $this->assertEquals([new TestAttribute('on a class')], $attributes);
  }

  public function testSetReflector(): void {
    $reflector = new \ReflectionClass(TestClassWithAttributes::class);
    $attributes = get_attributes($reflector, ReflectorAwareTestAttribute::class);
    $this->assertSame($reflector, $attributes[0]->reflector);

    $attributes = get_raw_attributes($reflector, ReflectorAwareTestAttribute::class);
    $this->assertNull($attributes[0]->reflector);
  }

  /**
   * @param \Closure(\ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant, class-string, int=): list<object> $get_attributes
   */
  #[DataProvider('providerGetAttributesFunction')]
  public function testByType(\Closure $get_attributes): void {
    $reflector = new \ReflectionClass(TestClassWithAttributes::class);

    $attributes = $get_attributes($reflector, TestAttributeInterface::class);
    $this->assertEquals([
      new TestAttribute('on a class'),
      new OtherTestAttribute('on a class'),
    ], $attributes);

    $attributes = $get_attributes($reflector, OtherTestAttribute::class);
    $this->assertEquals([
      // The array index starts at 0, even if an earlier attribute was omitted.
      new OtherTestAttribute('on a class'),
    ], $attributes);

    $attributes = $get_attributes($reflector, \stdClass::class);
    $this->assertEquals([], $attributes);

    $attributes = $get_attributes($reflector, TestAttributeInterface::class, 0);
    $this->assertEquals([], $attributes);
  }

  /**
   * @param \Closure(\ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant, class-string, int=): list<object> $get_attributes
   */
  #[DataProvider('providerGetAttributesFunction')]
  public function testOnDifferentReflectors(\Closure $get_attributes): void {
    $function = #[TestAttribute('on anonymous function')] fn() => NULL;
    $reflector = new \ReflectionFunction($function);
    $attributes = $get_attributes($reflector, TestAttribute::class);
    $this->assertEquals([new TestAttribute('on anonymous function')], $attributes);

    $reflector = new \ReflectionMethod(TestClassWithAttributes::class, 'foo');
    $attributes = $get_attributes($reflector, TestAttribute::class);
    $this->assertEquals([new TestAttribute('on a method')], $attributes);

    $reflector = $reflector->getParameters()[0];
    $attributes = $get_attributes($reflector, TestAttribute::class);
    $this->assertEquals([new TestAttribute('on a parameter')], $attributes);

    $reflector = new \ReflectionProperty(TestClassWithAttributes::class, 'x');
    $attributes = $get_attributes($reflector, TestAttribute::class);
    $this->assertEquals([new TestAttribute('on a property')], $attributes);

    $reflector = new \ReflectionClassConstant(TestClassWithAttributes::class, 'SOME_CONST');
    $attributes = $get_attributes($reflector, TestAttribute::class);
    $this->assertEquals([
      new TestAttribute('on a class constant'),
      new TestAttribute('another one on a class constant'),
    ], $attributes);
  }

  /**
   * @return array<string, array{\Closure(\ReflectionClass|\ReflectionFunctionAbstract|\ReflectionParameter|\ReflectionProperty|\ReflectionClassConstant, class-string, int=): list<object>}>
   */
  public static function providerGetAttributesFunction(): array {
    return [
      'get_attributes' => [get_attributes(...)],
      'get_raw_attributes' => [get_raw_attributes(...)],
    ];
  }

}
