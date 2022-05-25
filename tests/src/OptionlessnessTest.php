<?php

/**
 * @noinspection PhpDocMissingThrowsInspection
 * @noinspection PhpUnhandledExceptionInspection
 */

namespace Donquixote\Ock\Tests;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\GeneratorException;
use Donquixote\Ock\Generator\Generator;
use Donquixote\Ock\Generator\GeneratorInterface;
use Donquixote\Ock\Optionlessness\Optionlessness;
use Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface;
use Donquixote\Ock\Tests\Util\TestUtil;
use Donquixote\Ock\Util\PhpUtil;
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
    $optionless = Optionlessness::checkFormula($formula, $this->getAdapter());
    TestUtil::assertFileContents(
      "$dir/$base.is.optionless.yml",
      Yaml::dump($optionless),
    );
  }

}
