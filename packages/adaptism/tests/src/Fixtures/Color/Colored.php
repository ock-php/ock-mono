<?php

declare(strict_types=1);

namespace Ock\Adaptism\Tests\Fixtures\Color;

use Ock\Adaptism\Attribute\Adapter;
use Ock\Adaptism\Attribute\Parameter\AdapterTargetType;
use Ock\Adaptism\Tests\Fixtures\Color\Hex\HexColorInterface;
use Ock\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;
use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class Colored {

  /**
   * @template T as object
   *
   * @param \Ock\Adaptism\Tests\Fixtures\Color\Colored $colored
   * @param class-string<T> $targetType
   * @param \Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return object|null
   * @phpstan-return T|null
   * @psalm-return T|null
   *
   * @throws \Ock\Adaptism\Exception\AdapterException
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
