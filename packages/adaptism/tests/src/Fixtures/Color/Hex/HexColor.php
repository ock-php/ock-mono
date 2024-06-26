<?php
declare(strict_types=1);

namespace Ock\Adaptism\Tests\Fixtures\Color\Hex;

use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\Attribute\Parameter\Adaptee;
use Ock\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;

class HexColor implements HexColorInterface {

  /**
   * @param string $hexCode
   */
  public function __construct(
    private readonly string $hexCode,
  ) {}

  /**
   * @param \Ock\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface $rgbColor
   *
   * @return self
   */
  #[Adapter]
  public static function fromRgb(
    #[Adaptee] RgbColorInterface $rgbColor,
  ): self {
    return new self(
      sprintf(
        '%02x%02x%02x',
        $rgbColor->red(), $rgbColor->green(), $rgbColor->blue()));
  }

  /**
   * @return string
   *   The 6-char hex representation. Without any leading "#".
   */
  public function getHexCode(): string {
    return $this->hexCode;
  }
}
