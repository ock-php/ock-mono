<?php

namespace Ock\ClassDiscovery\Tests;

use Ock\ClassDiscovery\NamespaceDirectory;
use Ock\ClassDiscovery\Reflection\ClassReflection;
use Ock\ClassDiscovery\Tests\Fixtures\Acme\Plant\PlantInterface;
use Ock\ClassDiscovery\Tests\Fixtures\Acme\Plant\Tree\Fig;
use Ock\ClassDiscovery\Tests\Fixtures\Acme\Plant\VenusFlyTrap;
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
  }

  public function testFindNamespace(): void {
    // It does not work from a child namespace towards a top namespace.
    $this->assertNull(
      $this
        ->nsdirFromClass(PlantInterface::class)
        ->findNamespace(__NAMESPACE__),
    );

    // It does work from a parent namespace to a child namespace.
    $this->assertNamespaceDir(
      __DIR__ . '/Fixtures/Acme/Plant',
      __NAMESPACE__ . '\\Fixtures\\Acme\\Plant',
      $this
        ->nsdirFromClass(self::class)
        ->findNamespace(__NAMESPACE__ . '\\Fixtures\\Acme\\Plant') ?? $this->fail(),
    );

    // It also works for non-existing directory.
    $this->assertNamespaceDir(
      __DIR__ . '/NonExisting',
      __NAMESPACE__ . '\\NonExisting',
      $this
        ->nsdirFromClass(self::class)
        ->findNamespace(__NAMESPACE__ . '\\NonExisting') ?? $this->fail(),
    );
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
    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $this->assertNamespaceDir(
      $nsdir->getDirectory(),
      $nsdir->getNamespace(),
      $nsdir->parentN(0) ?? $this->fail('->parentN(0) is NULL.'),
    );
    $this->assertNamespaceDir(
      __DIR__,
      __NAMESPACE__,
      $nsdir->parentN(3) ?? $this->fail('->parentN(3) is NULL.'),
    );
    $this->assertNull($nsdir->parentN(4));
  }

  public function testParent(): void {
    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $this->assertNamespaceDir(
      __DIR__ . '/Fixtures/Acme',
      __NAMESPACE__ . '\\Fixtures\\Acme',
      $nsdir->parent() ?? $this->fail('->parent() is NULL.'),
    );
    $this->assertNull($this->nsdirFromClass(self::class)->parent());
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
    $this->assertSame(
      'Tests',
      $this->nsdirFromClass(self::class)->getShortname(),
    );
    $this->assertNull(
      $this->nsdir('/path/to/root/nsp', '')->getTerminatedShortname(),
    );
  }

  public function testGetTerminatedShortname(): void {
    $this->assertSame(
      'Tests\\',
      $this->nsdirFromClass(self::class)->getTerminatedShortname(),
    );
    $this->assertNull(
      $this->nsdir('/path/to/root/nsp', '')->getTerminatedShortname(),
    );
  }

  public function testGetShortFqn(): void {
    $this->assertSame(
      '\\Tests',
      $this->nsdirFromClass(self::class)->getShortFqn(),
    );
    $this->assertSame(
      '',
      $this->nsdir('/path/to/root/nsp', '')->getShortFqn(),
    );
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
    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $this->assertSame('Fixtures\\Acme\\Plant\\', $nsdir->getRelativeTerminatedNamespace(3));
    $this->assertSame('Tests\\Fixtures\\Acme\\Plant\\', $nsdir->getRelativeTerminatedNamespace());
    $this->assertSame('Acme\\Plant\\', $nsdir->getRelativeTerminatedNamespace(4));
    // It does not work with level: 2.
    $this->expectException(\RuntimeException::class);
    $nsdir->getPackageDirectory();
    $this->assertSame(
      __NAMESPACE__ . '\\',
      $this->nsdirFromClass(self::class)->getTerminatedNamespace(),
    );
    $this->assertSame(
      '',
      $this->nsdir('/path/to/root/nsp', '')->getTerminatedNamespace(),
    );
  }

  public function testGetRelativeFqn(): void {
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

  public function testGetReflectionClassesIA(): void {
    $nsdir = $this->nsdirFromClass(PlantInterface::class);
    $rfia = $nsdir->getReflectionClassesIA();
    $classes = [];
    foreach ($rfia as $key => $value) {
      $this->assertInstanceOf(ClassReflection::class, $value);
      $classes[$key] = $value->getClassName();
    }
    $this->assertSame(
      [
        PlantInterface::class,
        Fig::class,
        VenusFlyTrap::class,
      ],
      $classes,
    );
  }

  /**
   * Asserts that a namespace directory object is as expected.
   *
   * @param string $dir
   *   Expected directory.
   * @param string $namespace
   *   Expected namespace.
   * @param \Ock\ClassDiscovery\NamespaceDirectory $namespace_directory
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
      $this->assertSame($exception_class, get_class($e));
    }
  }

  /**
   * @param string $directory
   * @param string $namespace
   *
   * @return \Ock\ClassDiscovery\NamespaceDirectory
   */
  protected function nsdir(string $directory, string $namespace): NamespaceDirectory {
    $nsdir = NamespaceDirectory::create($directory, $namespace);
    $this->registerImmutableObject($nsdir);
    return $nsdir;
  }

  /**
   * @param class-string $class
   *
   * @return \Ock\ClassDiscovery\NamespaceDirectory
   */
  protected function nsdirFromClass(string $class): NamespaceDirectory {
    $nsdir = NamespaceDirectory::fromKnownClass($class);
    $this->registerImmutableObject($nsdir);
    return $nsdir;
  }

}
