<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ParametricMovie;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;
use Ock\DependencyInjection\Attribute\Service;

class MovieAtlasStaticMethod {

  #[Service]
  public static function create(
    #[GetParametricService('romance', 'Twilight')]
    Movie $twilightMovie,
  ): MovieAtlas {
    return new MovieAtlas($twilightMovie);
  }

}
