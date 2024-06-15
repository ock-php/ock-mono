<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\ServiceAttribute;

use Ock\DependencyInjection\Attribute\Service;

class ReturnSelfMethod {

  #[Service]
  #[Service(serviceId: 'return_self_id')]
  #[Service(serviceIdSuffix: '.suffix')]
  #[Service(target: 'exampleTarget')]
  public static function create(): self {
    return new self();
  }

}
