<?php

declare(strict_types = 1);

namespace Ock\DependencyInjection\Tests\Fixtures\StaticFactoryMethod;

class ClassA {

  public function __construct(
    public readonly DependencyService $dependencyService,
  ) {}

}
