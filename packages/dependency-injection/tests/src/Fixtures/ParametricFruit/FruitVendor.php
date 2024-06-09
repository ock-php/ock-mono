<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ParametricFruit;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;
use Ock\DependencyInjection\Attribute\PrivateService;

/**
 * A service with multiple parametric and non-parametric dependencies.
 */
class FruitVendor {

  private function __construct(
    public readonly OtherService $otherService,
    public readonly ParametricFruit $fruit,
    public readonly ParametricFruit $otherFruit,
  ) {}

  #[PrivateService]
  public static function createParametric(
    #[GetParametricService(0)]
    ParametricFruit $fruit,
    #[GetParametricService(1)]
    ParametricFruit $otherFruit,
    OtherService $otherService,
  ): self {
    return new self($otherService, $fruit, $otherFruit);
  }

}
