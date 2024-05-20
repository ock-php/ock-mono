<?php

declare(strict_types=1);

namespace Ock\Adaptism\Tests\Fixtures\Value;

use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\Attribute\Parameter\Adaptee;
use Ock\DID\Attribute\Parameter\GetService;

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
