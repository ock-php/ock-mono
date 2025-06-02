<?php

namespace Ock\ClassFilesIterator\Tests;

use Ock\ClassFilesIterator\DirectoryContents;
use Ock\ClassFilesIterator\NamespaceDirectory;
use Ock\ClassFilesIterator\Tests\Traits\ExceptionTestTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[UsesClass(NamespaceDirectory::class)]
#[CoversClass(DirectoryContents::class)]
class DirectoryContentsTest extends TestCase {

  use ExceptionTestTrait;

  public function testLoad(): void {
    $dir = __DIR__ . '/Fixtures/Acme/Plant';
    $this->assertEquals(
      new DirectoryContents(
        [
          'Bad.Dirname',
          'BadDir.php',
          'Tree',
        ],
        [
          'Bad.Name.php',
          'BadFileName',
          'PlantInterface.php',
          'VenusFlyTrap.php',
        ],
      ),
      DirectoryContents::load($dir),
    );
  }

  public function testGetFilesAndDirectoriesMap(): void {
    $contents = new DirectoryContents(
      [
        'item_3',
        'item_1',
        'item_2',
      ],
      [
        'item_3.txt',
        'item_1.txt',
        'item_2.txt',
      ],
    );
    $this->assertSame([
      'item_1' => true,
      'item_1.txt' => false,
      'item_2' => true,
      'item_2.txt' => false,
      'item_3' => true,
      'item_3.txt' => false,
    ], $contents->getFilesAndDirectoriesMap());
  }

  public function testIterateClassAndNamespaceMap(): void {
    $contents = new DirectoryContents(
      [
        'ItemC',
        'ItemA',
        'ItemB',
        'BadDirName.php',
        'Bad.Dirname',
      ],
      [
        'ItemC.php',
        'ItemA.php',
        'ItemB.php',
        'README.md',
      ],
    );
    $result = [];
    foreach ($contents->iterateClassAndNamespaceMap() as $name => $is_namespace) {
      $result[] = [$name, $is_namespace];
    }
    $this->assertSame([
      ['ItemA', true],
      ['ItemA', false],
      ['ItemB', true],
      ['ItemB', false],
      ['ItemC', true],
      ['ItemC', false],
    ], $result);
  }

  public function testGetClassNames(): void {
    $contents = new DirectoryContents(
      [
        'Subdir',
      ],
      [
        'ItemC.php',
        'ItemA.php',
        'ItemB.php',
        'README.md',
      ],
    );
    // Classes are returned in the original order.
    $this->assertSame([
      'ItemC',
      'ItemA',
      'ItemB',
    ], $contents->getClassNames());
  }

  public function testNamespaceNames(): void {
    $contents = new DirectoryContents(
      [
        'NamespaceC',
        'NamespaceA',
        'NamespaceB',
        'Bad.Subdir',
      ],
      [
        'C.php',
      ],
    );
    // Namespaces are returned in the original order.
    $this->assertSame([
      'NamespaceC',
      'NamespaceA',
      'NamespaceB',
    ], $contents->getNamespaceNames());
  }

}
