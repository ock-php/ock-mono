<?php

declare(strict_types=1);

namespace Ock\Adaptism\Tests\Fixtures\Value;

use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\Attribute\Parameter\Adaptee;
use Ock\DID\Attribute\Parameter\GetService;

class Timestamp {

  public function __construct(
    private readonly int $timestamp,
  ) {}

  #[Adapter]
  public static function fromDateTime(
    #[Adaptee] \DateTime $dateTime,
  ): self {
    return new self($dateTime->getTimestamp());
  }

  #[Adapter]
  public static function fromDateTimeString(
    #[Adaptee] LocalDateTimeString $dateTimeString,
    #[GetService] \DateTimeZone $dateTimeZone,
  ): self {
    return new self(
      \strtotime(
        $dateTimeString . ' ' . $dateTimeZone->getName()));
  }

  public function getTimestamp(): int {
    return $this->timestamp;
  }

}
