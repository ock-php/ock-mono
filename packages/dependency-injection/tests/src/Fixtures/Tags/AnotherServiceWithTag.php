<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Tests\Fixtures\Tags;

use Ock\DependencyInjection\Attribute\PrivateService;
use Ock\DependencyInjection\Attribute\ServiceTag;

class AnotherServiceWithTag {

  #[PrivateService]
  #[ServiceTag('sunny')]
  public static function create(): self {
    return new self();
  }

}
