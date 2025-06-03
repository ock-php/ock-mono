<?php

namespace Ock\ClassFilesIterator\Tests;

use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIA;
use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIA_Concat;
use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIA_Empty;
use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIA_Psr4;
use Ock\ClassFilesIterator\DirectoryContents;
use Ock\ClassFilesIterator\NamespaceDirectory;
use Ock\ClassFilesIterator\NsDirUtil;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Animal\GreySquirrel;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Animal\RedSquirrel;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\PlantInterface;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\Tree\Fig;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\VenusFlyTrap;
use Ock\ClassFilesIterator\Tests\Traits\ExceptionTestTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ClassFilesIA::class)]
#[CoversClass(ClassFilesIA_Concat::class)]
#[CoversClass(ClassFilesIA_Empty::class)]
#[CoversClass(ClassFilesIA_Psr4::class)]
#[UsesClass(NamespaceDirectory::class)]
#[UsesClass(NsDirUtil::class)]
#[UsesClass(DirectoryContents::class)]
class ClassFilesIATest extends TestCase {

  use ExceptionTestTrait;

  public function testCreate(): void {
    $f = fn (string $namespace) => ClassFilesIA_Psr4::create('path/to', $namespace);
    $c = fn (string $terminated_namespace) => new ClassFilesIA_Psr4('path/to', $terminated_namespace);
    $this->assertEquals($c(''), $f(''));
    $this->assertEquals($c('Acme\\'), $f('Acme'));
    $this->assertEquals($c('Acme\\Zoo\\'), $f('Acme\\Zoo'));
  }

  public function testCreateFromClass(): void {
    $f = ClassFilesIA_Psr4::createFromClass(...);
    $this->assertEquals(
      new ClassFilesIA_Psr4(__DIR__, __NAMESPACE__ . '\\'),
      $f(self::class),
    );
    $this->assertEquals(
      new ClassFilesIA_Psr4(
        __DIR__ . '/Fixtures/Acme/Plant/Tree',
        __NAMESPACE__ . '\Fixtures\Acme\Plant\Tree\\',
      ),
      $f(Fig::class, 0),
    );
    $this->assertEquals(
      new ClassFilesIA_Psr4(
        __DIR__ . '/Fixtures/Acme',
        __NAMESPACE__ . '\Fixtures\Acme\\',
      ),
      $f(Fig::class, 2),
    );
    $this->assertEquals(
      new ClassFilesIA_Psr4(
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
    $f = fn (string $dir) => ClassFilesIA_Psr4::createFromNsdirObject(NamespaceDirectory::create($dir, 'Acme\Zoo'));
    $c = fn (string $dir) => new ClassFilesIA_Psr4($dir, 'Acme\Zoo\\');
    $this->assertEquals($c(__DIR__), $f(__DIR__));
    $this->assertEquals(new ClassFilesIA_Empty(), $f(__DIR__ . '/non/existing/subdir'));
  }

  /**
   * @throws \ReflectionException
   */
  public function testGetIterator(): void {
    $classFilesIA = ClassFilesIA_Psr4::createFromClass(
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
    $this->callAndAssertException(\RuntimeException::class, ClassFilesIA_Psr4::create(__DIR__ . '/non/existing/subdir', 'Acme\\Zoo')->getIterator(...));
  }

  public function testEmpty(): void {
    $classFilesIA = new ClassFilesIA_Empty();
    $this->assertFalse($classFilesIA->getIterator()->valid());
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
  }

  public function testFactoryPsr4(): void {
    $this->assertEquals(
      new ClassFilesIA_Psr4('path/to', 'Acme\Zoo\\'),
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
