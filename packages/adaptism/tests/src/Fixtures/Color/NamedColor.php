<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Color;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColorInterface;

class NamedColor {

  const MAP = [
    'black' => '000000',
    'white' => 'ffffff',
    'red' => 'ff0000',
    'green' => '00ff00',
    'blue' => '0000ff',
  ];

  public function __construct(
    private readonly string $color,
  ) {}

  #[Adapter]
  public static function fromHex(
    #[Adaptee] HexColorInterface $hexColor,
  ): ?self {
    $names = \array_keys(self::MAP, $hexColor->getHexCode());
    return $names ? new self(reset($names)) : null;
  }

  /**
   * @return string
   *   A color name like 'black' or 'red'.
   */
  public function getColor(): string {
    return $this->color;
  }

}
