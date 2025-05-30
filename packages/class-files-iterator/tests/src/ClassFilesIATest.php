<?php

namespace Ock\ClassFilesIterator\Tests;

use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIA;
use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIA_Concat;
use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIA_Empty;
use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIA_NamespaceDirectoryPsr4;
use Ock\ClassFilesIterator\NamespaceDirectory;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Animal\GreySquirrel;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Animal\RedSquirrel;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\PlantInterface;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\Tree\Fig;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\VenusFlyTrap;
use PHPUnit\Framework\TestCase;

class ClassFilesIATest extends TestCase {

  use ExceptionTestTrait;

  public function testCreate(): void {
    $f = fn (string $namespace) => ClassFilesIA_NamespaceDirectoryPsr4::create('path/to', $namespace);
    $c = fn (string $terminated_namespace) => new ClassFilesIA_NamespaceDirectoryPsr4('path/to', $terminated_namespace);
    $this->assertEquals($c(''), $f(''));
    $this->assertEquals($c('Acme\\'), $f('Acme'));
    $this->assertEquals($c('Acme\\Zoo\\'), $f('Acme\\Zoo'));
  }

  public function testCreateFromClass(): void {
    $f = ClassFilesIA_NamespaceDirectoryPsr4::createFromClass(...);
    $this->assertEquals(
      new ClassFilesIA_NamespaceDirectoryPsr4(__DIR__, __NAMESPACE__ . '\\'),
      $f(self::class),
    );
    $this->assertEquals(
      new ClassFilesIA_NamespaceDirectoryPsr4(
        __DIR__ . '/Fixtures/Acme/Plant/Tree',
        __NAMESPACE__ . '\Fixtures\Acme\Plant\Tree\\',
      ),
      $f(Fig::class, 0),
    );
    $this->assertEquals(
      new ClassFilesIA_NamespaceDirectoryPsr4(
        __DIR__ . '/Fixtures/Acme',
        __NAMESPACE__ . '\Fixtures\Acme\\',
      ),
      $f(Fig::class, 2),
    );
    $this->assertEquals(
      new ClassFilesIA_NamespaceDirectoryPsr4(
        __DIR__,
        __NAMESPACE__ . '\\',
      ),
      $f(Fig::class, -3),
    );
    // @phpstan-ignore argument.type
    $this->callAndAssertException(\ReflectionException::class, fn () => $f(__NAMESPACE__ . '\\NonExistingClass'));
    $this->callAndAssertException(\RuntimeException::class, fn () => $f(Fig::class, 5));
    $this->callAndAssertException(\RuntimeException::class, fn () => $f(Fig::class, -2));
  }

  public function testCreateFromNsdirObject(): void {
    $f = fn (string $dir) => ClassFilesIA_NamespaceDirectoryPsr4::createFromNsdirObject(NamespaceDirectory::create($dir, 'Acme\Zoo'));
    $c = fn (string $dir) => new ClassFilesIA_NamespaceDirectoryPsr4($dir, 'Acme\Zoo\\');
    $this->assertEquals($c(__DIR__), $f(__DIR__));
    $this->assertEquals(new ClassFilesIA_Empty(), $f(__DIR__ . '/non/existing/subdir'));
  }

  public function testWithRealpathRoot(): void {
    $c = fn (string $dir) => new ClassFilesIA_NamespaceDirectoryPsr4($dir, 'Acme\Zoo\\');
    $realpath = realpath(__DIR__) ?: $this->fail();
    $crooked_path = __DIR__ . '/../src';
    $this->assertFalse(str_ends_with($realpath, '/../src'));
    $obj_with_realpath = $c($realpath);
    $this->assertEquals($obj_with_realpath, $c($crooked_path)->withRealpathRoot());

    // A new clone is returned even though values are the same.
    // Perhaps this will change in the future.
    $this->assertEquals($obj_with_realpath, $obj_with_realpath->withRealpathRoot());
    $this->assertNotSame($obj_with_realpath, $obj_with_realpath->withRealpathRoot());

    // Test effect on the iterator.
    $this->assertSame($realpath, dirname($obj_with_realpath->getIterator()->key()));
    $this->assertSame($crooked_path, dirname($c($crooked_path)->getIterator()->key()));
  }

  /**
   * @throws \ReflectionException
   */
  public function testGetIterator(): void {
    $classFilesIA = ClassFilesIA_NamespaceDirectoryPsr4::createFromClass(
      PlantInterface::class,
      1);
    $classFiles = iterator_to_array($classFilesIA->getIterator());
    $this->assertSame([
      __DIR__ . '/Fixtures/Acme/Animal/GreySquirrel.php' => GreySquirrel::class,
      __DIR__ . '/Fixtures/Acme/Animal/RedSquirrel.php' => RedSquirrel::class,
      __DIR__ . '/Fixtures/Acme/Plant/PlantInterface.php' => PlantInterface::class,
      __DIR__ . '/Fixtures/Acme/Plant/Tree/Fig.php' => Fig::class,
      __DIR__ . '/Fixtures/Acme/Plant/VenusFlyTrap.php' => VenusFlyTrap::class,
    ], $classFiles);
    $this->callAndAssertException(\RuntimeException::class, ClassFilesIA_NamespaceDirectoryPsr4::create(__DIR__ . '/non/existing/subdir', 'Acme\\Zoo')->getIterator()->valid(...));
  }

  public function testEmpty(): void {
    $classFilesIA = new ClassFilesIA_Empty();
    $this->assertFalse($classFilesIA->getIterator()->valid());
    $this->assertSame($classFilesIA, $classFilesIA->withRealpathRoot());
  }

  /**
   * @throws \ReflectionException
   */
  public function testConcat(): void {
    $concat = new ClassFilesIA_Concat([
      ClassFilesIA::psr4FromClass(Fig::class),
      ClassFilesIA::psr4FromClass(GreySquirrel::class),
      // Insert a duplicated segment.
      ClassFilesIA::psr4FromClass(Fig::class),
    ]);
    $actual = [];
    foreach ($concat as $file => $class) {
      $actual[] = [$file, $class];
    }
    $this->assertSame([
      [__DIR__ . '/Fixtures/Acme/Plant/Tree/Fig.php', Fig::class],
      [__DIR__ . '/Fixtures/Acme/Animal/GreySquirrel.php', GreySquirrel::class],
      [__DIR__ . '/Fixtures/Acme/Animal/RedSquirrel.php', RedSquirrel::class],
      [__DIR__ . '/Fixtures/Acme/Plant/Tree/Fig.php', Fig::class],
    ], $actual);

    $this->assertEquals(
      new ClassFilesIA_Concat([
        ClassFilesIA::psr4(realpath(__DIR__) ?: '?', 'Acme\Zoo'),
        ClassFilesIA::psr4(realpath(__DIR__ . '/Fixtures') ?: '?', 'Acme\Zoo'),
      ]),
      (new ClassFilesIA_Concat([
        ClassFilesIA::psr4(__DIR__ . '/../src', 'Acme\Zoo'),
        ClassFilesIA::psr4(__DIR__ . '/../src/Fixtures', 'Acme\Zoo'),
      ]))->withRealpathRoot(),
    );
  }

  public function testFactoryPsr4(): void {
    $this->assertEquals(
      new ClassFilesIA_NamespaceDirectoryPsr4('path/to', 'Acme\Zoo\\'),
      ClassFilesIA::psr4('path/to', 'Acme\Zoo'),
    );
  }

  public function testFactoryPsr4Up(): void {
    $f = ClassFilesIA::psr4Up(...);
    $fn = fn (int ...$args) => $f('path/to/Animal', 'Acme\Zoo\Animal', ...$args);
    $this->assertEquals($default = NamespaceDirectory::create(
      'path/to/Animal',
      'Acme\Zoo\Animal',
    ), $fn());
    $this->assertEquals($default, $fn(0));
    $this->assertEquals(NamespaceDirectory::create(
      'path/to',
      'Acme\Zoo',
    ), $fn(1));
    $this->assertEquals(NamespaceDirectory::create(
      'path/to',
      'Acme\Zoo',
    ), $fn(-2));
  }

  public function testFactoryPsr4FromClass(): void {
    foreach ([
      ClassFilesIA::psr4FromClass(...),
      ClassFilesIA::psr4FromKnownClass(...),
    ] as $f) {
      $fn = fn (int ...$args) => $f(Fig::class, ...$args);
      $this->assertEquals($default = NamespaceDirectory::create(
        __DIR__ . '/Fixtures/Acme/Plant/Tree',
        __NAMESPACE__ . '\Fixtures\Acme\Plant\Tree',
      ), $fn());
      $this->assertEquals($default, $fn(0));
      $this->assertEquals(NamespaceDirectory::create(
        __DIR__ . '/Fixtures/Acme/Plant',
        __NAMESPACE__ . '\Fixtures\Acme\Plant',
      ), $fn(1));
      $this->assertEquals(NamespaceDirectory::create(
        __DIR__,
        __NAMESPACE__,
      ), $fn(-3));
    }
  }

  public function testFactoryMultiple(): void {
    $parts = [new ClassFilesIA_Empty()];
    $this->assertEquals(
      new ClassFilesIA_Concat($parts),
      ClassFilesIA::multiple($parts),
    );
  }

  public function testFactoryPsr4FromClasses(): void {
    $this->assertEquals(
      new ClassFilesIA_Concat([
        NamespaceDirectory::create(__DIR__, __NAMESPACE__),
        NamespaceDirectory::create(
          __DIR__ . '/Fixtures/Acme/Plant/Tree',
          __NAMESPACE__ . '\Fixtures\Acme\Plant\Tree',
        ),
      ]),
      ClassFilesIA::psr4FromClasses([
        self::class,
        Fig::class,
      ]),
    );
  }

}
