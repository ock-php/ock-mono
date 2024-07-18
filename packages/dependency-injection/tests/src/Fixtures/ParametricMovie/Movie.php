<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ParametricMovie;

use Ock\DependencyInjection\Attribute\Parameter\GetParametricArgument;

class Movie {

  public function __construct(
    public readonly MovieHelperService1 $helperService1,
    #[GetParametricArgument(0)]
    public readonly string $genre,
    #[GetParametricArgument(1)]
    public readonly string $name,
    public readonly MovieHelperService2 $helperService2,
  ) {}

}
