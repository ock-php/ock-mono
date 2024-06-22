<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ParametricColors;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;
use Ock\DependencyInjection\Attribute\Service;

#[Service]
class ServiceWithYellowColor {

  public function __construct(
    #[GetParametricService('yellow')]
    public readonly Color $amarillo,
  ) {}

}
