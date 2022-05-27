<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Value;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;

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
