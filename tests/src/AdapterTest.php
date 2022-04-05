<?php
declare(strict_types=1);

namespace Donquixote\Adaptism\Tests;

use Donquixote\Adaptism\Tests\Fixtures\Color\Colored;
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColor;
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColorInterface;
use Donquixote\Adaptism\Tests\Fixtures\Color\NamedColor;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColor;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;
use Donquixote\Adaptism\Tests\Fixtures\FixturesUtil;
use Donquixote\Adaptism\Tests\Fixtures\Value\LocalDateTimeString;
use Donquixote\Adaptism\Tests\Fixtures\Value\Timestamp;
use PHPUnit\Framework\TestCase;

class AdapterTest extends TestCase {

  /**
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  public function test(): void {
    $adapter = FixturesUtil::getUniversalAdapter();

    $traversable = (static function () {yield 'a'; yield 'b';})();
    $countable = $adapter->adapt($traversable, \Countable::class);
    self::assertInstanceOf(\Countable::class, $countable);
    self::assertSame(2, $countable->count());

    $rgb = new RgbColor(255, 50, 80);
    $hex = $adapter->adapt($rgb, HexColorInterface::class);
    self::assertInstanceOf(HexColorInterface::class, $hex);
    self::assertSame('ff3250', $hex->getHexCode());

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

    self::assertSame('green', $adapter->adapt(
      new HexColor('00ff00'),
      NamedColor::class,
    )->getColor());

    $colored = new Colored(new RgbColor(255, 50, 80));
    $hex = $adapter->adapt($colored, HexColorInterface::class);
    self::assertInstanceOf(HexColorInterface::class, $hex);
    self::assertSame('ff3250', $hex->getHexCode());

    $timestring = new LocalDateTimeString('2022-04-04');
    $timestamp = $adapter->adapt($timestring, Timestamp::class);
    self::assertInstanceOf(Timestamp::class, $timestamp);
    self::assertSame(1649044800, $timestamp->getTimestamp());

    $timestringCanonical = $adapter->adapt($timestamp, LocalDateTimeString::class);
    self::assertInstanceOf(LocalDateTimeString::class, $timestringCanonical);
    self::assertSame('2022-04-04T00:00:00', $timestringCanonical->__toString());
  }

}
