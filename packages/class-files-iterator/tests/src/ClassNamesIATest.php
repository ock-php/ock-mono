<?php

namespace Ock\ClassFilesIterator\Tests;

use Ock\ClassFilesIterator\ClassNamesIA\ClassNamesIA_Array;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Animal\RedSquirrel;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\Tree\Fig;
use PHPUnit\Framework\TestCase;

class ClassNamesIATest extends TestCase {

  use ExceptionTestTrait;

  public function testArray(): void {
    $classes = [
      'a' => Fig::class,
      RedSquirrel::class,
    ];
    $this->assertSame($classes, iterator_to_array(
      new ClassNamesIA_Array($classes),
    ));
  }

}
