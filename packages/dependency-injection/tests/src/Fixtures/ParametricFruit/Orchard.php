<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ParametricFruit;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;
use Ock\DependencyInjection\Attribute\PrivateService;

class Orchard implements OrchardInterface {

  private function __construct(
    public readonly ParametricFruit $fruit,
  ) {}

  #[PrivateService]
  public static function createParametric(
    #[GetParametricService]
    ParametricFruit $fruit,
  ): OrchardInterface {
    return new self($fruit);
  }

}
