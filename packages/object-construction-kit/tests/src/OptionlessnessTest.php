<?php

declare(strict_types=1);

namespace Ock\Ock\Tests;

use Ock\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Optionlessness\Optionlessness;
use Ock\Ock\Tests\Util\TestingServices;
use Ock\Ock\Tests\Util\TestUtil;
use Symfony\Component\Yaml\Yaml;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class OptionlessnessTest extends FormulaTestBase {

  /**
   * Tests various formulas.
   *
   * @param string $base
   *
   * @dataProvider providerTestFormula()
   */
  public function testFormula(string $base): void {
    $dir = dirname(__DIR__) . '/fixtures/formula';
    /** @psalm-suppress UnresolvableInclude */
    $formula = include "$dir/$base.php";
    self::assertInstanceOf(FormulaInterface::class, $formula);

    $container = TestingServices::getContainer();
    $adapter = $container->get(UniversalAdapterInterface::class);

    $optionless = Optionlessness::checkFormula($formula, $adapter);
    TestUtil::assertFileContents(
      "$dir/$base.is.optionless.yml",
      Yaml::dump($optionless),
    );
  }

}
