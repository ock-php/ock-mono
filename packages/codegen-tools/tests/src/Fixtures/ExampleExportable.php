<?php

declare(strict_types = 1);

namespace Ock\CodegenTools\Tests\Fixtures;

use Ock\CodegenTools\Attribute\TrivialExport;

#[TrivialExport]
class ExampleExportable {

  public function __construct(
    public readonly int $x,
    public readonly string $y,
    public readonly object $z,
  ) {}

}
