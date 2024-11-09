<?php

declare(strict_types = 1);

namespace Ock\DependencyInjection\Tests\Fixtures\StaticFactoryInterfaceAutowire;

class ClassC implements InterfaceI {

  public function __construct(
    public readonly ?DependencyD $dependencyD,
  ) {}

}
