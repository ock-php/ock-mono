<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Tests;

use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_ClassFilesIA;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_ClassNamesIA;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Ock\ClassDiscovery\Tests\Defunct\ClassWithInterfaceMissing;
use Ock\ClassDiscovery\Tests\Fixtures\Acme\Animal\RedSquirrel;
use Ock\ClassDiscovery\Tests\Fixtures\Acme\Plant\VenusFlyTrap;
use Ock\ClassDiscovery\Tests\Fixtures\NonLoadingInterface;
use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface;
use Ock\ClassFilesIterator\ClassFilesIA\RealpathRootThisTrait;
use Ock\ClassFilesIterator\ClassNamesIA\ClassNamesIAInterface;
use PHPUnit\Framework\TestCase;

class ReflectionClassesIATest extends TestCase {

  public function testFromClassNamesIA(): void {
    $classNames = [
      VenusFlyTrap::class,
      // Non-loading classes are skipped.
      NonLoadingInterface::class,
      // Classes with non-loading interface are skipped.
      ClassWithInterfaceMissing::class,
      RedSquirrel::class,
      // Repeated classes are kept.
      VenusFlyTrap::class,
    ];
    $expectedRemainingClassNames = [
      VenusFlyTrap::class,
      RedSquirrel::class,
      VenusFlyTrap::class,
    ];

    $classNamesIA = new class($classNames) extends \ArrayObject implements ClassNamesIAInterface {};
    $reflectionClassesIA = new ReflectionClassesIA_ClassNamesIA($classNamesIA);
    $this->assertReflectionClassNames($expectedRemainingClassNames, $reflectionClassesIA);
  }

  public function testFromClassFilesIA(): void {
    $classNames = [
      __DIR__ . '/Fixtures/Acme/Plant/VenusFlyTrap.php' => VenusFlyTrap::class,
      // Non-loading classes are skipped.
      __DIR__ . '/non-existing-file.php' => NonLoadingInterface::class,
      // Classes with missing interface or base class are skipped.
      __DIR__ . '/Defunct/ClassWithInterfaceMissing.php' => ClassWithInterfaceMissing::class,
      __DIR__ . '/Fixtures/Acme/Animal/RedSquirrel.php' => RedSquirrel::class,
    ];
    $expectedRemainingClassNames = [
      VenusFlyTrap::class,
      RedSquirrel::class,
    ];
    $classFilesIA = new class($classNames) extends \ArrayObject implements ClassFilesIAInterface {
      use RealpathRootThisTrait;
    };
    $reflectionClassesIA = new ReflectionClassesIA_ClassFilesIA($classFilesIA);
    $this->assertReflectionClassNames($expectedRemainingClassNames, $reflectionClassesIA);
  }

  public function testSkipWrongPath(): void {
    $classNames = [
      __DIR__ . '/Fixtures/Acme/Plant/VenusFlyTrap.php' => VenusFlyTrap::class,
      // Classes with the wrong path are skipped.
      __DIR__ . '/Fixtures/Acme/Plant/RedSquirrel.php' => RedSquirrel::class,
    ];
    $expectedRemainingClassNames = [
      VenusFlyTrap::class,
    ];
    $classFilesIA = new class($classNames) extends \ArrayObject implements ClassFilesIAInterface {
      use RealpathRootThisTrait;
    };
    $reflectionClassesIA = new ReflectionClassesIA_ClassFilesIA($classFilesIA);
    $this->assertReflectionClassNames($expectedRemainingClassNames, $reflectionClassesIA);
  }

  /**
   * @param list<class-string> $expectedClassNames
   * @param \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface $reflectionClassesIA
   * @param class-string<\ReflectionClass>|null $expectedReflectionClass
   */
  protected function assertReflectionClassNames(array $expectedClassNames, ReflectionClassesIAInterface $reflectionClassesIA, string $expectedReflectionClass = NULL): void {
    $actualClassNames = [];
    foreach ($reflectionClassesIA as $i => $reflectionClass) {
      $this->assertInstanceOf(\ReflectionClass::class, $reflectionClass);
      if ($expectedReflectionClass !== NULL) {
        $this->assertInstanceOf($expectedReflectionClass, $reflectionClass);
      }
      $actualClassNames[$i] = $reflectionClass->name;
    }
    $this->assertSame($expectedClassNames, $actualClassNames);
  }

}
