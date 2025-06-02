<?php

declare(strict_types = 1);

namespace Ock\ClassFilesIterator\Tests;

use Ock\ClassFilesIterator\NamespaceDirectory;
use Ock\ClassFilesIterator\NsDirUtil;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\PlantInterface;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\Tree\Fig;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\VenusFlyTrap;
use Ock\ClassFilesIterator\Tests\Traits\ExceptionTestTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NsDirUtil::class)]
#[UsesClass(NamespaceDirectory::class)]
class NsDirUtilTest extends TestCase {

  use ExceptionTestTrait;

  public function testTerminateNamespace(): void {
    $f = NsDirUtil::terminateNamespace(...);
    $this->assertSame('Acme\Zoo\Animal\\', $f('Acme\Zoo\Animal'));
    $this->assertSame('Acme\\', $f('Acme'));
    $this->assertSame('', $f(''));
    $this->callAndAssertException(\InvalidArgumentException::class, fn () => $f('Acme\\Zoo\\Animal\\'));
    $this->callAndAssertException(\InvalidArgumentException::class, fn () => $f('\\Acme\\Zoo\\Animal'));
  }

  public function testIterate(): void {
    $nsdir = NamespaceDirectory::fromKnownClass(PlantInterface::class);
    $this->assertSame(
      [
        __DIR__ . '/Fixtures/Acme/Plant/PlantInterface.php' => PlantInterface::class,
        __DIR__ . '/Fixtures/Acme/Plant/Tree/Fig.php' => Fig::class,
        __DIR__ . '/Fixtures/Acme/Plant/VenusFlyTrap.php' => VenusFlyTrap::class,
      ],
      iterator_to_array(NsDirUtil::iterate($nsdir->getDirectory(), $nsdir->getTerminatedNamespace())),
    );

    $assert_exception = fn (string $path) => $this->callAndAssertException(\RuntimeException::class, fn () => NsDirUtil::iterate($path, ''));

    $assert_exception(__DIR__ . '/NonExistingSubdir');
    $assert_exception(__FILE__);

    mkdir($dir = sys_get_temp_dir() . '/test-dir-' . uniqid());
    $perms = fileperms($dir) & 0777;
    file_put_contents($file = $dir . '/Test.php', "<?php return 'file contents';");
    $this->assertSame($file, NsDirUtil::iterate($dir, '')->key());

    // Write and "execute" permissions on the directory.
    chmod($dir, $perms & 0333);
    $assert_exception($dir);
    $this->assertSame('file contents', include $file);

    // Only read permissions, no execute bit.
    // The directory will appear as empty.
    chmod($dir, $perms & 0444);
    $this->assertNull(NsDirUtil::iterate($dir, '')->key());
    $this->assertFalse(@include $file);
  }

  public function testAssertReadableDirectory(): void {
    $f = NsDirUtil::assertReadableDirectory(...);
    $assert_exception = fn(string $path) => $this->callAndAssertException(\RuntimeException::class, fn () => $f($path));

    $f(__DIR__);
    $assert_exception(__DIR__ . '/NonExistingSubdir');
    $assert_exception(__FILE__);

    mkdir($dir = sys_get_temp_dir() . '/test-dir-' . uniqid());
    $perms = fileperms($dir) & 0777;
    touch($file = $dir . '/Test.php');
    $f($dir);

    // Write and "execute" permissions.
    chmod($dir, $perms & 0333);
    $assert_exception($dir);

    // Only read permissions, no execute bit.
    // The directory will appear as empty.
    chmod($dir, $perms & 0444);
    $f($dir);
  }

  public function testScanKnownDir(): void {
    $f = NsDirUtil::scanKnownDir(...);
    $this->callAndAssertException(\RuntimeException::class, fn () => $f(__DIR__ . '/NonExistingDir'));
  }

}
