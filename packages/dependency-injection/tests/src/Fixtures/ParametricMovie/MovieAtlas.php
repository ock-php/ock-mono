<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ParametricMovie;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricService;
use Ock\DependencyInjection\Attribute\Service;

#[Service]
class MovieAtlas {

  public function __construct(
    #[GetParametricService('romance', 'Twilight')]
    public readonly Movie $twilightMovie,
  ) {}

}
