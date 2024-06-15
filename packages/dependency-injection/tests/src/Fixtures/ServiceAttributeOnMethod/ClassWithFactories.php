<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ServiceAttributeOnMethod;

use Ock\DependencyInjection\Attribute\Service;

class ClassWithFactories {

  #[Service]
  public static function createExample(): Example {
    return new Example();
  }

}
