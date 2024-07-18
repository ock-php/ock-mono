<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ParametricMovie;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;
use Ock\DependencyInjection\Attribute\Service;
use PHPUnit\Framework\Assert;

#[Service]
class MovieAtlas {

  public function __construct(
    #[GetParametricService('romance', 'Twilight')]
    public readonly Movie $twilightMovie,
  ) {
    Assert::assertSame('romance', $twilightMovie->genre);
    Assert::assertSame('Twilight', $twilightMovie->name);
  }

}
