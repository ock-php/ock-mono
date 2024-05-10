<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

namespace Donquixote\Adaptism\Tests;

use Donquixote\Adaptism\Tests\Fixtures\Color\AltRgbColor;
use Donquixote\Adaptism\Tests\Fixtures\Color\Colored;
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColor;
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColorInterface;
use Donquixote\Adaptism\Tests\Fixtures\Color\NamedColor;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColor;
use Donquixote\Adaptism\Tests\Fixtures\FixturesUtil;
use Donquixote\Adaptism\Tests\Fixtures\Value\LocalDateTimeString;
use Donquixote\Adaptism\Tests\Fixtures\Value\Timestamp;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use PHPUnit\Framework\TestCase;

class AdapterTest extends TestCase {

  public function test(): void {
    $container = FixturesUtil::getContainer();
    $adapter = $container->get(UniversalAdapterInterface::class);

    self::assertSame(2, $adapter->adapt(
      (static function () {yield 'a'; yield 'b';})(),
      \Countable::class,
    )->count());

    self::assertSame('ff3250', $adapter->adapt(
      new RgbColor(255, 50, 80),
      HexColorInterface::class,
    )->getHexCode());

    self::assertNull($adapter->adapt(
      new RgbColor(255, 50, 80),
      NamedColor::class,
    ));

    self::assertNull($adapter->adapt(
      new HexColor('aaff00'),
      NamedColor::class,
    ));

    self::assertSame('ff0000', $adapter->adapt(
      new RgbColor(255, 0, 0),
      HexColor::class,
    )->getHexCode());

    self::assertSame('red', $adapter->adapt(
      new RgbColor(255, 0, 0),
      NamedColor::class,
    )->getColor());

    self::assertSame('blue', $adapter->adapt(
      new AltRgbColor(0, 0, 255),
      NamedColor::class,
    )->getColor());

    self::assertSame('green', $adapter->adapt(
      new HexColor('00ff00'),
      NamedColor::class,
    )->getColor());

    self::assertSame('ff3250', $adapter->adapt(
      new Colored(new RgbColor(255, 50, 80)),
      HexColorInterface::class,
    )->getHexCode());

    self::assertSame(1649044800, $adapter->adapt(
      new LocalDateTimeString('2022-04-04'),
      Timestamp::class,
    )->getTimestamp());

    self::assertSame('2022-04-04T00:00:00', $adapter->adapt(
      new Timestamp(1649044800),
      LocalDateTimeString::class,
    )->__toString());
  }

}
