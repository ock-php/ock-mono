<?php

declare(strict_types = 1);

namespace Ock\DependencyInjection\Tests\Fixtures\StaticFactoryMethod;

use Ock\DependencyInjection\Attribute\Service;

class ClassWithStaticFactories {

  #[Service]
  public static function createClassA(
    DependencyService $dependencyService,
  ): ClassA {
    return new ClassA($dependencyService);
  }

  #[Service]
  public static function createClassZ(
    DependencyService $dependencyService,
  ): ClassZ {
    return new ClassZ($dependencyService);
  }

}
