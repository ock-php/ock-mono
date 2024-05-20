<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

namespace Ock\Adaptism\Tests;

use Ock\Adaptism\AdapterDefinition\AdapterDefinition_Simple;
use Ock\Adaptism\AdapterDefinitionList\AdapterDefinitionListInterface;
use Ock\Adaptism\SpecificAdapter\SpecificAdapter_Callback;
use Ock\Adaptism\SpecificAdapter\SpecificAdapter_Construct;
use Ock\Adaptism\Tests\Fixtures\Color\Colored;
use Ock\Adaptism\Tests\Fixtures\Color\Hex\HexColor;
use Ock\Adaptism\Tests\Fixtures\Color\Rgb\RgbColorInterface;
use Ock\Adaptism\Tests\Fixtures\Countable\Countable_Traversable;
use Ock\Adaptism\Tests\Fixtures\FixturesUtil;
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
      SpecificAdapter_Construct::egg(
        Countable_Traversable::class,
        false,
        [],
      ),
    );

    $expected[HexColor::class . '::fromRgb'] = new AdapterDefinition_Simple(
      RgbColorInterface::class,
      HexColor::class,
      0,
      SpecificAdapter_Callback::egg(
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
      SpecificAdapter_Callback::egg(
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
