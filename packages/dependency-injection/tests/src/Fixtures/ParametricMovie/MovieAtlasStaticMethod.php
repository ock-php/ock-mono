<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ParametricMovie;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;
use Ock\DependencyInjection\Attribute\Service;
use PHPUnit\Framework\Assert;

class MovieAtlasStaticMethod {

  #[Service]
  public static function create(
    #[GetParametricService('romance', 'Twilight')]
    Movie $twilightMovie,
  ): MovieAtlas {
    Assert::assertSame('romance', $twilightMovie->genre);
    Assert::assertSame('Twilight', $twilightMovie->name);
    return new MovieAtlas($twilightMovie);
  }

}
