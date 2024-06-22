<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ParametricFruit;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;
use Ock\DependencyInjection\Attribute\Service;

class Orchard implements OrchardInterface {

  private function __construct(
    public readonly ParametricFruit $fruit,
  ) {}

  #[Service]
  public static function createParametric(
    #[GetParametricService(0)]
    ParametricFruit $fruit,
  ): OrchardInterface {
    return new self($fruit);
  }

}
