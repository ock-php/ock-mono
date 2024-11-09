<?php

declare(strict_types = 1);

namespace Ock\DependencyInjection\Tests\Fixtures\StaticFactoryInterfaceAutowire;

use Ock\DependencyInjection\Attribute\Service;

class Factories {

  #[Service]
  public static function createService(
    DependencyD $dependencyD = null,
  ): InterfaceI {
    return new ClassC($dependencyD);
  }

}
