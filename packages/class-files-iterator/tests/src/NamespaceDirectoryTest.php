<?php

namespace Ock\ClassFilesIterator\Tests;

use Ock\ClassFilesIterator\NamespaceDirectory;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\PlantInterface;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\Tree\Fig;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\VenusFlyTrap;
use PHPUnit\Framework\TestCase;

class NamespaceDirectoryTest extends TestCase {

  use ImmutableObjectsTrait;

  public function testCreate(): void {
    $this->assertNamespaceDir(
      __DIR__,
      __NAMESPACE__,
      NamespaceDirectory::create(__DIR__, __NAMESPACE__),
    );
    // Non-existing class is no problem.
    $this->assertNamespaceDir(
      __DIR__ . '/NonExisting',
      __NAMESPACE__ . '\\NonExisting',
      NamespaceDirectory::create(__DIR__ . '/NonExisting', __NAMESPACE__ . '\\NonExisting'),
    );
  }

  public function testFromKnownClass(): void {
    $this->assertNamespaceDir(
      __DIR__,
      __NAMESPACE__,
      NamespaceDirectory::fromKnownClass(self::class),
    );
    // Does not work with non-existing class.
    $this->callAndAssertException(
      \RuntimeException::class,
      // @phpstan-ignore argument.type
      fn () => NamespaceDirectory::fromKnownClass(__NAMESPACE__ . '\\NonExistingClass'),
    );
    // Does not work with built-in class.
    $this->callAndAssertException(
      \InvalidArgumentException::class,
      fn() => NamespaceDirectory::fromKnownClass(\stdClass::class),
    );
  }

  public function testCreateFromClass(): void {
    $this->assertNamespaceDir(
      __DIR__,
      __NAMESPACE__,
      NamespaceDirectory::createFromClass(self::class),
    );
    // Does not work with non-existing class.
    $this->callAndAssertException(
      \ReflectionException::class,
      fn () => NamespaceDirectory::createFromClass(__NAMESPACE__ . '\\NonExistingClass'),
    );
    // Does not work with built-in class.
    $this->callAndAssertException(
      \InvalidArgumentException::class,
      fn () => NamespaceDirectory::createFromClass(\stdClass::class),
    );
  }

  public function testFromReflectionClass(): void {
    $this->assertNamespaceDir(
      __DIR__,
      __NAMESPACE__,
      NamespaceDirectory::fromReflectionClass(new \ReflectionClass(self::class)),
    );
    // Does not work with built-in class.
    $this->callAndAssertException(
      \InvalidArgumentException::class,
      fn () => NamespaceDirectory::fromReflectionClass(new \ReflectionClass(\stdClass::class)),
    );
  }

  public function testWithRealpathRoot(): void {
    $this->assertNamespaceDir(
      __DIR__,
      __NAMESPACE__,
      $this
        ->nsdir(__DIR__ . '/../src', __NAMESPACE__)
        ->withRealpathRoot(),
    );
    $this->callAndAssertException(
      \RuntimeException::class,
      fn () => $this
        ->nsdir(__DIR__ . '/non/existing/path', 'Acme\Foo')
        ->withRealpathRoot(),
    );
  }

  public function testFindNamespace(): void {
    $nsdir = $this->nsdir('path/to/package/src/Animal/Mammal', 'Acme\Zoo\Animal\Mammal');
    $this->assertSame($nsdir, $nsdir->findNamespace('Acme\Zoo\Animal\Mammal'));
    // Find child namespace.
    $this->assertNamespaceDir(
      'path/to/package/src/Animal/Mammal/Whale',
      $namespace = 'Acme\Zoo\Animal\Mammal\Whale',
      $nsdir->findNamespace($namespace) ?? $this->fail(),
    );
    $this->assertNamespaceDir(
      'path/to/package/src/Animal/Mammal/Whale/Dolphin',
      $namespace = 'Acme\Zoo\Animal\Mammal\Whale\Dolphin',
      $nsdir->findNamespace($namespace) ?? $this->fail(),
    );
    // Cannot find parent namespace.
    $this->assertNull($nsdir->findNamespace('Acme\Zoo\Animal'));
    // Cannot find sibling namespace.
    $this->assertNull($nsdir->findNamespace('Acme\Zoo\Animal\Insect'));
  }

  public function testBasedir(): void {
    $this->assertNamespaceDir(
      __DIR__,
      __NAMESPACE__,
      $this
        ->nsdirFromClass(PlantInterface::class)
        ->basedir(),
    );
    // Calling it again changes nothing.
    $this->assertNamespaceDir(
      __DIR__,
      __NAMESPACE__,
      $this
        ->nsdirFromClass(PlantInterface::class)
        ->basedir()
        ->basedir(),
    );
  }

  public function testVendor(): void {
    $this->assertNamespaceDir(
      '/path/to/project/vendor/acmecorp',
      'Acme',
      $this
        ->nsdir('/path/to/project/vendor/acmecorp/Zoo/Animal', 'Acme\\Zoo\\Animal')
        ->vendor(),
    );

    // It does not work if there is an '/src/' dir in between.
    $this->expectException(\RuntimeException::class);
    $this->nsdirFromClass(PlantInterface::class)->vendor();
  }

  public function testPackage(): void {
    $this->assertNamespaceDir(
      '/path/to/project/vendor/acmecorp/zoopkg',
      'Acme\\Zoo',
      $this
        ->nsdir('/path/to/project/vendor/acmecorp/zoopkg/Animal/Butterfly', 'Acme\\Zoo\\Animal\\Butterfly')
        ->package(),
    );

    $this->assertNamespaceDir(
      '/path/to/project/vendor/acmecorp/zoopkg/Animal',
      'Acme\\Zoo\\Animal',
      $this
        ->nsdir('/path/to/project/vendor/acmecorp/zoopkg/Animal/Butterfly', 'Acme\\Zoo\\Animal\\Butterfly')
        ->package(3),
    );

    // It does not work if there is an '/src/' dir in between.
    $this->expectException(\RuntimeException::class);
    $this->nsdirFromClass(PlantInterface::class)->package();
  }

  public function testRequireParentAt(): void {
    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $this->assertNamespaceDir(
      $nsdir->getDirectory(),
      $nsdir->getNamespace(),
      $nsdir->requireParentAt(6),
    );
    $this->assertNamespaceDir(
      __DIR__,
      __NAMESPACE__,
      $nsdir->requireParentAt(3),
    );
    $this->expectException(\RuntimeException::class);
    $nsdir->requireParentAt(2);
  }

  public function testRequireParentN(): void {
    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $this->assertNamespaceDir(
      $nsdir->getDirectory(),
      $nsdir->getNamespace(),
      $nsdir->requireParentN(0),
    );
    $this->assertNamespaceDir(
      __DIR__,
      __NAMESPACE__,
      $nsdir->requireParentN(3),
    );
    $this->expectException(\RuntimeException::class);
    $nsdir->requireParentN(4);
  }

  public function testRequireParent(): void {
    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $this->assertNamespaceDir(
      __DIR__ . '/Fixtures/Acme',
      __NAMESPACE__ . '\\Fixtures\\Acme',
      $nsdir->requireParent(),
    );
    $this->expectException(\RuntimeException::class);
    $this->nsdirFromClass(self::class)->requireParent();
  }

  public function testParentN(): void {
    $nsdir = $this->nsdir(
      'path/to/package/src/Animal/Mammal',
      'Acme\Zoo\Animal\Mammal',
    );
    $this->assertSame($nsdir, $nsdir->parentN(0));
    $this->assertNamespaceDir(
      'path/to/package/src/Animal',
      'Acme\Zoo\Animal',
      $nsdir->parentN(1) ?? $this->fail(),
    );
    $this->assertNamespaceDir(
      'path/to/package/src',
      'Acme\Zoo',
      $nsdir->parentN(2) ?? $this->fail(),
    );
    $this->assertNull($nsdir->parentN(3));
    $this->assertNull($nsdir->parentN(4));
    $this->assertNull($nsdir->parentN(-1));
    $this->assertNamespaceDir(
      'path/to/package/src',
      'Acme\Zoo',
      $nsdir->parentN(-2) ?? $this->fail(),
    );
    $this->assertNamespaceDir(
      'path/to/package/src/Animal',
      'Acme\Zoo\Animal',
      $nsdir->parentN(-3) ?? $this->fail(),
    );
    $this->assertSame($nsdir, $nsdir->parentN(-4));
    $this->assertNull($nsdir->parentN(-5));
    $this->assertNull($nsdir->parentN(-6));
  }

  public function testParent(): void {
    $f = fn (string $dir, string $namespace) => $this->nsdir($dir, $namespace)->parent();
    $this->assertNamespaceDir(
      'path/to/Animal',
      'Acme\Zoo\Animal',
      $f(
        'path/to/Animal/Mammal',
        'Acme\Zoo\Animal\Mammal',
      ) ?? $this->fail(),
    );
    $this->assertNull($f('path/to/package/src', 'Acme\Zoo'));
    $this->assertNull($f('path/to/package', ''));
    $this->assertNull($f('', 'Acme'));
    $this->assertNull($f('path-without-slash', 'Acme\Zoo'));
    $this->assertNamespaceDir(
      '',
      'Acme',
      $f('Zoo', 'Acme\Zoo') ?? $this->fail(),
    );
    $this->assertNamespaceDir(
      'path/to/package',
      '',
      $f('path/to/package/Acme', 'Acme') ?? $this->fail(),
    );
  }

  public function testSubdir(): void {
    $nsdir = $this->nsdirFromClass(self::class);
    $this->assertNamespaceDir(
      __DIR__ . '/NonExisting',
      __NAMESPACE__ . '\\NonExisting',
      $nsdir->subdir('NonExisting'),
    );
  }

  public function testGetNamespace(): void {
    $this->assertSame(
      __NAMESPACE__,
      $this->nsdirFromClass(self::class)->getNamespace(),
    );
    $this->assertSame(
      '',
      $this->nsdir('/path/to/root/nsp', '')->getNamespace(),
    );
  }

  public function testGetShortname(): void {
    $f = fn (string $namespace) => $this->nsdir('path/to', $namespace)->getShortname();
    $this->assertSame('Animal', $f('Acme\Zoo\Animal'));
    $this->assertSame('Acme',$f('Acme'));
    $this->assertNull($f(''));
  }

  public function testGetTerminatedShortname(): void {
    $f = fn (string $namespace) => $this->nsdir('path/to', $namespace)->getTerminatedShortname();
    $this->assertSame('Animal\\', $f('Acme\Zoo\Animal'));
    $this->assertSame('Acme\\', $f('Acme'));
    $this->assertNull($f(''));
  }

  public function testGetShortFqn(): void {
    $f = fn (string $namespace) => $this->nsdir('path/to', $namespace)->getShortFqn();
    $this->assertSame('\\Animal', $f('Acme\Zoo\Animal'));
    $this->assertSame('\\Acme', $f('Acme'));
    $this->assertSame('', $f(''));
  }

  public function testGetTerminatedNamespace(): void {
    $this->assertSame(
      __NAMESPACE__ . '\\',
      $this->nsdirFromClass(self::class)->getTerminatedNamespace(),
    );
    $this->assertSame(
      '',
      $this->nsdir('/path/to/root/nsp', '')->getTerminatedNamespace(),
    );
  }

  public function testGetFqn(): void {
    $this->assertSame(
      '\\' . __NAMESPACE__,
      $this->nsdirFromClass(self::class)->getFqn(),
    );
    $this->assertSame(
      '',
      $this->nsdir('/path/to/root/nsp', '')->getFqn(),
    );
  }

  public function testGetDirectory(): void {
    $this->assertSame(
      __DIR__,
      $this->nsdirFromClass(self::class)->getDirectory(),
    );
  }

  public function testGetTerminatedPath(): void {
    $this->assertSame(
      __DIR__ . '/',
      $this->nsdirFromClass(self::class)->getTerminatedPath(),
    );
  }

  public function testGetPackageDirectory(): void {
    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $this->assertSame(dirname(__DIR__), $nsdir->getPackageDirectory(level: 3));
    $this->assertSame(__DIR__, $nsdir->getPackageDirectory('', 3));
    $this->assertSame(__DIR__ . '/Fixtures', $nsdir->getPackageDirectory('', 4));
    // It does not work with level: 2.
    $this->expectException(\RuntimeException::class);
    $nsdir->getPackageDirectory();
  }

  public function testGetRelativeTerminatedNamespace(): void {
    // @phpstan-ignore argument.type
    $f = fn (string $namespace, int ...$args) => $this->nsdir('path/to', $namespace)->getRelativeTerminatedNamespace(...$args);
    $fn = fn (int ...$args) => $f('Acme\Zoo\Animal\Mammal', ...$args);
    $this->assertSame('Animal\Mammal\\', $fn());
    $this->assertSame('Acme\Zoo\Animal\Mammal\\', $fn(0));
    $this->assertSame('Zoo\Animal\Mammal\\', $fn(1));
    $this->assertSame('Animal\Mammal\\', $fn(2));
    $this->assertSame('Mammal\\', $fn(3));
    $this->assertSame('', $fn(4));
    $this->callAndAssertException(\RuntimeException::class, fn () => $fn(5));
    $this->assertSame('', $f('', 0));
    $this->callAndAssertException(\RuntimeException::class, fn () => $f('', 1));
    $this->assertSame('Acme\\', $f('Acme', 0));
    $this->assertSame('', $f('Acme', 1));
    $this->callAndAssertException(\RuntimeException::class, fn () => $f('Acme', 2));
  }

  public function testGetRelativeFqn(): void {
    $f = fn (string $namespace, int ...$args) => $this->nsdir('path/to', $namespace)->getRelativeFqn(...$args);
    $fn = fn (int ...$args) => $f('Acme\Zoo\Animal\Mammal', ...$args);
    $this->assertSame('\\Animal\Mammal', $fn());
    $this->assertSame('\\Acme\Zoo\Animal\Mammal', $fn(0));
    $this->assertSame('\\Zoo\Animal\Mammal', $fn(1));
    $this->assertSame('\\Animal\Mammal', $fn(2));
    $this->assertSame('\\Mammal', $fn(3));
    $this->assertSame('', $fn(4));
    $this->callAndAssertException(\RuntimeException::class, fn () => $fn(5));
    $this->assertSame('', $f('', 0));
    $this->callAndAssertException(\RuntimeException::class, fn () => $f('', 1));
    $this->assertSame('\\Acme', $f('Acme', 0));
    $this->assertSame('', $f('Acme', 1));
    $this->callAndAssertException(\RuntimeException::class, fn () => $f('Acme', 2));


    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $this->assertSame('\\Fixtures\\Acme\\Plant', $nsdir->getRelativeFqn(3));
    $this->assertSame('\\Tests\\Fixtures\\Acme\\Plant', $nsdir->getRelativeFqn());
    $this->assertSame('\\Acme\\Plant', $nsdir->getRelativeFqn(4));
  }

  public function testGetRelativePath(): void {
    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $this->assertSame('/src/Fixtures/Acme/Plant', $nsdir->getRelativePath(level: 3));
    $this->assertSame('/Fixtures/Acme/Plant', $nsdir->getRelativePath('', 3));
    $this->assertSame('/Acme/Plant', $nsdir->getRelativePath('', 4));
    // It does not work with level: 2.
    $this->expectException(\RuntimeException::class);
    $nsdir->getRelativePath();
  }

  public function testGetRelativeTerminatedPath(): void {
    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $this->assertSame('src/Fixtures/Acme/Plant/', $nsdir->getRelativeTerminatedPath(level: 3));
    $this->assertSame('Fixtures/Acme/Plant/', $nsdir->getRelativeTerminatedPath('', 3));
    $this->assertSame('Acme/Plant/', $nsdir->getRelativeTerminatedPath('', 4));
    // It does not work with level: 2.
    $this->expectException(\RuntimeException::class);
    $nsdir->getRelativeTerminatedPath();
  }

  public function testGetIterator(): void {
    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $this->assertSame(
      [
        __DIR__ . '/Fixtures/Acme/Plant/PlantInterface.php' => PlantInterface::class,
        __DIR__ . '/Fixtures/Acme/Plant/Tree/Fig.php' => Fig::class,
        __DIR__ . '/Fixtures/Acme/Plant/VenusFlyTrap.php' => VenusFlyTrap::class,
      ],
      iterator_to_array($nsdir->getIterator()),
    );
    $bad_nsdir = $this->nsdir(__DIR__ . '/non/existing/subdir', 'Acme\Missing');
    $this->callAndAssertException(\RuntimeException::class, fn () => $bad_nsdir->getIterator()->valid());
  }

  public function testGetElements(): void {
    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $this->assertEquals(
      [
        [
          __DIR__ . '/Fixtures/Acme/Plant/PlantInterface.php' => PlantInterface::class,
          __DIR__ . '/Fixtures/Acme/Plant/VenusFlyTrap.php' => VenusFlyTrap::class,
        ],
        [
          'Tree' => NamespaceDirectory::fromKnownClass(Fig::class),
        ],
      ],
      $nsdir->getElements(),
    );
  }

  public function testGetClassFilesHere(): void {
    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $this->assertSame(
      [
        __DIR__ . '/Fixtures/Acme/Plant/PlantInterface.php' => PlantInterface::class,
        __DIR__ . '/Fixtures/Acme/Plant/VenusFlyTrap.php' => VenusFlyTrap::class,
      ],
      $nsdir->getClassFilesHere(),
    );
  }

  public function testGetSubdirsHere(): void {
    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $this->assertEquals(
      [
        'Tree' => NamespaceDirectory::fromKnownClass(Fig::class),
      ],
      iterator_to_array($nsdir->getSubdirsHere()),
    );
  }

  /**
   * Asserts that a namespace directory object is as expected.
   *
   * @param string $dir
   *   Expected directory.
   * @param string $namespace
   *   Expected namespace.
   * @param \Ock\ClassFilesIterator\NamespaceDirectory $namespace_directory
   *   Namespace directory object to check.
   */
  protected function assertNamespaceDir(
    string $dir,
    string $namespace,
    NamespaceDirectory $namespace_directory,
  ): void {
    $this->assertSame($dir, $namespace_directory->getDirectory());
    $this->assertSame($namespace, $namespace_directory->getNamespace());
  }

  /**
   * @param class-string $exception_class
   * @param callable(): (void|mixed) $callback
   */
  protected function callAndAssertException(string $exception_class, callable $callback): void {
    // Does not work with non-existing class.
    try {
      $callback();
      $this->fail("Expected exception was not thrown.");
    }
    catch (\Throwable $e) {
      if (get_class($e) !== $exception_class) {
        throw $e;
      }
      $this->addToAssertionCount(1);
    }
  }

  /**
   * @param string $directory
   * @param string $namespace
   *
   * @return \Ock\ClassFilesIterator\NamespaceDirectory
   */
  protected function nsdir(string $directory, string $namespace): NamespaceDirectory {
    $nsdir = NamespaceDirectory::create($directory, $namespace);
    $this->registerImmutableObject($nsdir);
    return $nsdir;
  }

  /**
   * @param class-string $class
   *
   * @return \Ock\ClassFilesIterator\NamespaceDirectory
   */
  protected function nsdirFromClass(string $class): NamespaceDirectory {
    $nsdir = NamespaceDirectory::fromKnownClass($class);
    $this->registerImmutableObject($nsdir);
    return $nsdir;
  }

}
