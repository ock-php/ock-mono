<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\Tests\Fixtures\Color;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\AdapterTargetType;
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColorInterface;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class Colored {

  /**
   * @template T as object
   *
   * @param \Donquixote\Adaptism\Tests\Fixtures\Color\Colored $colored
   * @param class-string<T> $targetType
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return object|null
   * @phpstan-return T|null
   * @psalm-return T|null
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function adapt(
    Colored $colored,
    #[AdapterTargetType] string $targetType,
    UniversalAdapterInterface $universalAdapter,
  ): ?object {
    return $universalAdapter->adapt($colored->getColor(), $targetType);
  }

  public function __construct(
    private readonly HexColorInterface|RgbColorInterface $color,
  ) {}

  public function getColor(): HexColorInterface|RgbColorInterface {
    return $this->color;
  }

}
