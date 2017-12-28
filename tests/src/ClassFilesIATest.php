<?php

namespace Donquixote\ClassDiscovery\Tests;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_Empty;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_Multiple;
use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIA_NamespaceDirectoryPsr4;
use Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Animal\GreySquirrel;
use Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Plant\PlantInterface;
use Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Plant\Tree\Fig;
use PHPUnit\Framework\TestCase;

class ClassFilesIATest extends TestCase {

  public function testClassFilesIA() {

    $classFilesIA = ClassFilesIA_NamespaceDirectoryPsr4::createFromClass(
      PlantInterface::class,
      1);

    $classFiles = iterator_to_array($classFilesIA->getIterator());

    asort($classFiles);

    $this->assertSame(
      [
        __DIR__ . '/Fixtures/Acme/Animal/GreySquirrel.php' => 'Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Animal\GreySquirrel',
        __DIR__ . '/Fixtures/Acme/Animal/RedSquirrel.php' => 'Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Animal\RedSquirrel',
        __DIR__ . '/Fixtures/Acme/Plant/PlantInterface.php' => 'Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Plant\PlantInterface',
        __DIR__ . '/Fixtures/Acme/Plant/Tree/Fig.php' => 'Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Plant\Tree\Fig',
        __DIR__ . '/Fixtures/Acme/Plant/VenusFlyTrap.php' => 'Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Plant\VenusFlyTrap',
      ],
      $classFiles);
  }

  public function testClassFilesIAEmpty() {

    $classFilesIA = new ClassFilesIA_Empty();

    $classFiles = iterator_to_array($classFilesIA->getIterator());

    asort($classFiles);

    $this->assertSame([], $classFiles);
  }

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
        __DIR__ . '/Fixtures/Acme/Animal/GreySquirrel.php' => 'Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Animal\GreySquirrel',
        __DIR__ . '/Fixtures/Acme/Animal/RedSquirrel.php' => 'Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Animal\RedSquirrel',
        __DIR__ . '/Fixtures/Acme/Plant/Tree/Fig.php' => 'Donquixote\ClassDiscovery\Tests\Fixtures\Acme\Plant\Tree\Fig',
      ],
      $classFiles);
  }

}
