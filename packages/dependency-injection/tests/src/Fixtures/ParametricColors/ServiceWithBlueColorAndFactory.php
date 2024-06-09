<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ParametricColors;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;
use Ock\DependencyInjection\Attribute\Service;
use PHPUnit\Framework\Assert;

class ServiceWithBlueColorAndFactory {

  public function __construct(
    public readonly Color $blueColor,
  ) {}

  #[Service]
  public static function blue(
    #[GetParametricService('blue')]
    Color $azul,
  ): self {
    Assert::assertSame('blue', $azul->color);
    return new self($azul);
  }

}
