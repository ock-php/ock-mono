<?php

namespace Ock\ClassFilesIterator\Tests;

use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIA;
use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIA_Concat;
use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIA_Empty;
use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIA_NamespaceDirectoryPsr4;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Animal\GreySquirrel;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Animal\RedSquirrel;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\PlantInterface;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\Tree\Fig;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\VenusFlyTrap;
use PHPUnit\Framework\TestCase;

class ClassFilesIATest extends TestCase {

  /**
   * @throws \ReflectionException
   */
  public function testClassFilesIA(): void {
    $classFilesIA = ClassFilesIA_NamespaceDirectoryPsr4::createFromClass(
      PlantInterface::class,
      1);
    $classFiles = iterator_to_array($classFilesIA->getIterator());
    asort($classFiles);
    $this->assertSame([
      __DIR__ . '/Fixtures/Acme/Animal/GreySquirrel.php' => GreySquirrel::class,
      __DIR__ . '/Fixtures/Acme/Animal/RedSquirrel.php' => RedSquirrel::class,
      __DIR__ . '/Fixtures/Acme/Plant/PlantInterface.php' => PlantInterface::class,
      __DIR__ . '/Fixtures/Acme/Plant/Tree/Fig.php' => Fig::class,
      __DIR__ . '/Fixtures/Acme/Plant/VenusFlyTrap.php' => VenusFlyTrap::class,
    ], $classFiles);
  }

  public function testClassFilesIAEmpty(): void {
    $classFilesIA = new ClassFilesIA_Empty();
    $classFiles = iterator_to_array($classFilesIA->getIterator());
    asort($classFiles);
    $this->assertSame([], $classFiles);
  }

  /**
   * @throws \ReflectionException
   */
  public function testClassFilesIAMultiple(): void {
    $classFilesIAs = [];
    $classFilesIAs[] = ClassFilesIA::psr4FromClass(Fig::class);
    $classFilesIAs[] = ClassFilesIA::psr4FromClass(GreySquirrel::class);
    $classFilesIA = new ClassFilesIA_Concat($classFilesIAs);
    $classFiles = iterator_to_array($classFilesIA->getIterator());
    asort($classFiles);
    $this->assertSame([
      __DIR__ . '/Fixtures/Acme/Animal/GreySquirrel.php' => GreySquirrel::class,
      __DIR__ . '/Fixtures/Acme/Animal/RedSquirrel.php' => RedSquirrel::class,
      __DIR__ . '/Fixtures/Acme/Plant/Tree/Fig.php' => Fig::class,
    ], $classFiles);
  }

}
