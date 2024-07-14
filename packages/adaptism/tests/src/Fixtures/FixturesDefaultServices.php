<?php

declare(strict_types = 1);

namespace Ock\Adaptism\Tests\Fixtures;

use Ock\DependencyInjection\Attribute\Service;

class FixturesDefaultServices {

  #[Service]
  public static function getDateTimeZone(): \DateTimeZone {
    return new \DateTimeZone('America/New_York');
  }

}
