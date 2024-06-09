<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ServiceAttribute;

use Ock\DependencyInjection\Attribute\Service;

class ReturnStaticMethod {

  #[Service]
  #[Service(serviceId: 'return_static_id')]
  #[Service(serviceIdSuffix: '.suffix')]
  #[Service(target: 'exampleTarget')]
  public static function create(): static {
    // Extend at your own risk.
    // @phpstan-ignore-next-line
    return new static();
  }

}
