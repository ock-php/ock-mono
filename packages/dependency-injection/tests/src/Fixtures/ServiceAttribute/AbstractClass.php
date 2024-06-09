<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ServiceAttribute;

use Ock\DependencyInjection\Attribute\Service;

abstract class AbstractClass {

  #[Service]
  public static function createExample(): ClassWithoutAttribute {
    return new ClassWithoutAttribute();
  }

  #[Service(target: 'exampleTarget')]
  public static function createWithTarget(): ClassWithoutAttribute {
    return new ClassWithoutAttribute();
  }

  #[Service(serviceIdSuffix: 'exampleSuffix')]
  public static function createWithSuffix(): ClassWithoutAttribute {
    return new ClassWithoutAttribute();
  }

}
