<?php

namespace Ock\ClassFilesIterator\Tests;

use Ock\ClassFilesIterator\ClassNamesIA\ClassNamesIA_Array;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Animal\RedSquirrel;
use Ock\ClassFilesIterator\Tests\Fixtures\Acme\Plant\Tree\Fig;
use Ock\ClassFilesIterator\Tests\Traits\ExceptionTestTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ClassNamesIA_Array::class)]
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
