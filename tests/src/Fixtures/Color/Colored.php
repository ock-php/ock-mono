<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Color;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\AdapterTargetType;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColorInterface;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class Colored {

  /**
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function adapt(
    #[Adaptee] Colored $colored,
    #[AdapterTargetType] string $targetType,
    #[UniversalAdapter] UniversalAdapterInterface $universalAdapter,
  ): object {
    return $universalAdapter->adapt($colored->getColor(), $targetType);
  }

  public function __construct(
    private HexColorInterface|RgbColorInterface $color,
  ) {}

  public function getColor(): HexColorInterface|RgbColorInterface {
    return $this->color;
  }

}
