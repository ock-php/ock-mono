<?php

declare(strict_types=1);

namespace Ock\Adaptism\Tests\Fixtures\Color;

use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\Attribute\Parameter\Adaptee;
use Ock\Adaptism\Tests\Fixtures\Color\Rgb\RgbColor;

/**
 * Alternative implementation of RGB color.
 *
 * This tests a 3-step adaption.
 */
class AltRgbColor {

  public function __construct(
    private readonly int $r,
    private readonly int $g,
    private readonly int $b,
  ) {}

  #[Adapter]
  public static function convert(
    #[Adaptee] self $altRgb,
  ): RgbColor {
    return new RgbColor(...$altRgb->rgb());
  }

  /**
   * @return array{int, int, int}
   */
  public function rgb(): array {
    return [$this->r, $this->g, $this->b];
  }
}
