<?php

declare(strict_types = 1);

namespace Donquixote\CodegenTools\Tests\Fixtures;

use Donquixote\CodegenTools\Attribute\TrivialExport;

#[TrivialExport]
class ExampleExportable {

  public function __construct(
    public readonly int $x,
    public readonly string $y,
    public readonly object $z,
  ) {}

}
