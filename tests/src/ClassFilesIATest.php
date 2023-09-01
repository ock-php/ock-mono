<?php

namespace Donquixote\ClassDiscovery\Tests;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_Empty;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_Multiple;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_NamespaceDirectoryPsr4;
use Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Animal\GreySquirrel;
use Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Plant\PlantInterface;
use Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Plant\Tree\Fig;
use PHPUnit\Framework\TestCase;
use Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Animal\RedSquirrel;
use Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Plant\VenusFlyTrap;

class ClassFilesIATest extends TestCase {

  /**
   * @throws \ReflectionException
   */
  public function testClassFilesIA() {

    $classFilesIA = ClassFilesIA_NamespaceDirectoryPsr4::createFromClass(
      PlantInterface::class,
      1);

    $classFiles = iterator_to_array($classFilesIA->getIterator());

    asort($classFiles);

    $this->assertSame(
      [
        __DIR__ . '/Fixtures/Acme/Animal/GreySquirrel.php' => GreySquirrel::class,
        __DIR__ . '/Fixtures/Acme/Animal/RedSquirrel.php' => RedSquirrel::class,
        __DIR__ . '/Fixtures/Acme/Plant/PlantInterface.php' => PlantInterface::class,
        __DIR__ . '/Fixtures/Acme/Plant/Tree/Fig.php' => Fig::class,
        __DIR__ . '/Fixtures/Acme/Plant/VenusFlyTrap.php' => VenusFlyTrap::class,
      ],
      $classFiles);
  }

  public function testClassFilesIAEmpty() {

    $classFilesIA = new ClassFilesIA_Empty();

    $classFiles = iterator_to_array($classFilesIA->getIterator());

    asort($classFiles);

    $this->assertSame([], $classFiles);
  }

  /**
   * @throws \ReflectionException
   */
  public function testClassFilesIAMultiple() {

    $classFilesIAs = [];
    $classFilesIAs[] = ClassFilesIA_NamespaceDirectoryPsr4::createFromClass(
      Fig::class);
    $classFilesIAs[] = ClassFilesIA_NamespaceDirectoryPsr4::createFromClass(
      GreySquirrel::class);

    $classFilesIA = new ClassFilesIA_Multiple($classFilesIAs);

    $classFiles = iterator_to_array($classFilesIA->getIterator());

    asort($classFiles);

    $this->assertSame(
      [
        __DIR__ . '/Fixtures/Acme/Animal/GreySquirrel.php' => GreySquirrel::class,
        __DIR__ . '/Fixtures/Acme/Animal/RedSquirrel.php' => RedSquirrel::class,
        __DIR__ . '/Fixtures/Acme/Plant/Tree/Fig.php' => Fig::class,
      ],
      $classFiles);
  }

}
