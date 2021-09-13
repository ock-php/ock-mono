<?php

namespace Donquixote\Ock\Tests;

use Donquixote\CallbackReflection\Util\CodegenUtil;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\Formula\Drilldown\Option\DrilldownOption;
use Donquixote\Ock\Generator\Generator;
use Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface;
use Donquixote\Ock\Tests\Fixture\Plant\Plant_OakTree;
use Donquixote\Ock\Text\Text_Translatable;
use Symfony\Component\Yaml\Yaml;

class GeneratorTest extends FormulaTestBase {

  /**
   * Tests various formulas.
   *
   * @param string $file
   *   File containing the expected generated code.
   *
   * @dataProvider providerTestFormula()
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   */
  public function testFormula(string $file): void {
    $dir = dirname(__DIR__) . '/fixtures/formula';
    if (!preg_match('@^(\w+)\.(\w+)\.php$@', $file, $m)) {
      self::fail("Unexpected file name '$file'.");
    }
    list(, $base, $case) = $m;
    $formula = include "$dir/$base.php";
    if (!$formula instanceof FormulaInterface) {
      self::fail('Formula must implement FormulaInterface.');
    }
    $conf = Yaml::parseFile("$dir/$base.$case.yml");
    $formula_to_anything = $this->getFormulaToAnything();
    $generator = Generator::fromFormula(
      $formula,
      $formula_to_anything);
    $php_raw = $generator->confGetPhp($conf);
    $php_nice = CodegenUtil::autoIndent($php_raw, '  ');
    $php_statement = CodegenUtil::buildReturnStatement($php_nice);
    $php_actual = CodegenUtil::formatAsFile($php_statement);
    $php_expected = file_get_contents("$dir/$file");
    self::assertSame($php_expected, $php_actual);
  }

  /**
   * Data provider.
   *
   * @return \Iterator
   *   Parameter combos.
   */
  public function providerTestFormula(): \Iterator {
    $dir = dirname(__DIR__) . '/fixtures/formula';
    $candidates = scandir($dir);
    $candidates = preg_grep('@^(\w+)\.(\w+)\.php$@', $candidates);
    foreach ($candidates as $candidate) {
      yield [$candidate];
    }
  }

  /**
   * Tests an interface generator with a specific example.
   *
   * @param string $type
   *   Short id identifying the interface.
   * @param string $name
   *   Name of the test case.
   *
   * @dataProvider providerTestIfaceGenerator()
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   */
  public function testIfaceGenerator(string $type, string $name) {
    $interface = strtr(IntOpInterface::class, ['IntOp' => $type]);
    $filebase = dirname(__DIR__) . '/fixtures/iface/' . $type . '/' . $name;
    $conf = Yaml::parseFile($filebase . '.yml');
    $formula_to_anything = $this->getFormulaToAnything();
    $generator = Generator::fromIface(
      $interface,
      $formula_to_anything);
    $php_raw = $generator->confGetPhp($conf);
    $php_nice = CodegenUtil::autoIndent($php_raw, '  ', '  ');
    $php_statement = CodegenUtil::buildReturnStatement($php_nice);
    $php_statement = <<<EOT
return static function (): \\$interface {
  $php_statement
};
EOT;
    $php_actual = CodegenUtil::formatAsFile($php_statement);
    $php_expected = file_get_contents($filebase . '.php');
    self::assertSame($php_expected, $php_actual);
  }

  /**
   * Data provider.
   *
   * @return \Iterator|array[]
   *   Argument combos.
   */
  public function providerTestIfaceGenerator(): \Iterator {
    $dir = dirname(__DIR__) . '/fixtures/iface';
    foreach (scandir($dir) as $dir_candidate) {
      if ($dir_candidate === '.' || $dir_candidate === '..') {
        continue;
      }
      foreach (scandir($dir . '/' . $dir_candidate) as $file_candidate) {
        if (preg_match('@^(\w+)\.php$@', $file_candidate, $m)) {
          yield [$dir_candidate, $m[1]];
        }
      }
    }
  }

}
