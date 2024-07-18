<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ParametricFruit;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;

class ParametricFruitJuice {

  public function __construct(
    #[GetParametricService]
    public readonly ParametricFruit $fruit,
  ) {}

}
