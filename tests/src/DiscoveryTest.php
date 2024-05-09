<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

namespace Donquixote\Adaptism\Tests;

use Donquixote\Adaptism\AdapterDefinition\AdapterDefinition_Simple;
use Donquixote\Adaptism\AdapterDefinitionList\AdapterDefinitionListInterface;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_Callback;
use Donquixote\Adaptism\SpecificAdapter\SpecificAdapter_Construct;
use Donquixote\Adaptism\Tests\Fixtures\Color\Colored;
use Donquixote\Adaptism\Tests\Fixtures\Color\Hex\HexColor;
use Donquixote\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;
use Donquixote\Adaptism\Tests\Fixtures\Countable\Countable_Traversable;
use Donquixote\Adaptism\Tests\Fixtures\FixturesUtil;
use PHPUnit\Framework\TestCase;

class DiscoveryTest extends TestCase {

  public function testDefinitionListNotEmpty(): void {

    /** @var AdapterDefinitionListInterface $adapterDefinitionList */
    $adapterDefinitionList = FixturesUtil::getContainer()
      ->get(AdapterDefinitionListInterface::class);

    $definitions = $adapterDefinitionList->getDefinitions();

    self::assertNotEmpty($definitions);
  }

  public function testDefinitionList(): void {

    $expected = [];

    $expected[Countable_Traversable::class] = new AdapterDefinition_Simple(
      \Traversable::class,
      Countable_Traversable::class,
      0,
      SpecificAdapter_Construct::ctv(
        Countable_Traversable::class,
        false,
        [],
      ),
    );

    $expected[HexColor::class . '::fromRgb'] = new AdapterDefinition_Simple(
      RgbColorInterface::class,
      HexColor::class,
      0,
      SpecificAdapter_Callback::ctv(
        [HexColor::class, 'fromRgb'],
        false,
        false,
        [],
      ),
    );

    $expected[Colored::class . '::adapt'] = new AdapterDefinition_Simple(
      Colored::class,
      null,
      0,
      SpecificAdapter_Callback::ctv(
        [Colored::class, 'adapt'],
        true,
        true,
        [],
      ),
    );

    /** @var AdapterDefinitionListInterface $adapterDefinitionList */
    $adapterDefinitionList = FixturesUtil::getContainer()
      ->get(AdapterDefinitionListInterface::class);

    self::assertSameExportAndSort(
      $expected,
      \array_intersect_key(
        $adapterDefinitionList->getDefinitions(),
        $expected,
      ),
    );
  }

  /**
   * @param array $expected
   * @param array $actual
   */
  private static function assertSameExportAndSort(array $expected, array $actual): void {
    self::assertSame(
      self::exportItemsAndSort($expected),
      self::exportItemsAndSort($actual));
  }

  /**
   * @param mixed[] $items
   *
   * @return string
   */
  private static function exportItemsAndSort(array $items): string {

    $export = [];
    foreach ($items as $item) {
      $export[] = var_export($item, true);
    }

    array_multisort($export, $items);

    return var_export($items, true);
  }

}
