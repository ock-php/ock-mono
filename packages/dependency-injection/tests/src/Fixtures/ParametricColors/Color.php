<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ParametricColors;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricArgument;

class Color {

  public function __construct(
    #[GetParametricArgument]
    public readonly string $color,
  ) {}

}
