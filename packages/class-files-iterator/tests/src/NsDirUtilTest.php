<?php

declare(strict_types = 1);

namespace Ock\ClassFilesIterator\Tests;

use Ock\ClassFilesIterator\NsDirUtil;
use PHPUnit\Framework\TestCase;

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

}
