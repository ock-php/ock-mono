<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ParametricFruit;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricArgument;

class ParametricFruit {

  public function __construct(
    #[GetParametricArgument]
    public readonly string $name,
  ) {}

}
