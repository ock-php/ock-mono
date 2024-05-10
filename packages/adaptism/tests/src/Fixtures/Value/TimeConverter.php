<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Value;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\DID\Attribute\Parameter\GetService;

class TimeConverter {

  public function __construct(
    #[GetService] private readonly \DateTimeZone $timeZone,
  ) {}

  #[Adapter]
  public function convert(
    #[Adaptee] Timestamp $timestamp,
  ): LocalDateTimeString {
    /** @noinspection PhpUnhandledExceptionInspection */
    $date = new \DateTime('now', $this->timeZone);
    $date->setTimestamp($timestamp->getTimestamp());
    return new LocalDateTimeString($date->format('Y-m-d\TH:i:s'));
  }

}
