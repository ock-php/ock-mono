<?php

declare(strict_types=1);

namespace Ock\Adaptism\Tests\Fixtures\Value;

use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\Attribute\Parameter\Adaptee;

class LocalDateTimeString {

  public function __construct(
    private readonly string $dateTimeString,
  ) {}

  #[Adapter]
  public static function fromDateTime(
    #[Adaptee] \DateTime $dateTime,
  ): self {
    return new self($dateTime->format('Y-m-dTH:i:s'));
  }

  /**
   * @return string
   */
  public function __toString(): string {
    return $this->dateTimeString;
  }

}
