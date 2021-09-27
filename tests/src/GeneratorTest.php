<?php

namespace Donquixote\Ock\Tests;

use Donquixote\CallbackReflection\Util\CodegenUtil;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\GeneratorException;
use Donquixote\Ock\Generator\Generator;
use Donquixote\Ock\Generator\GeneratorInterface;
use Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface;
use Donquixote\Ock\Tests\Util\TestUtil;
use Symfony\Component\Yaml\Yaml;

class GeneratorTest extends FormulaTestBase {

  /**
   * Tests various formulas.
   *
   * @param string $file
   *   File containing the expected generated code.
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   *   (unexpected) Failure to create generator for formula.
   *
   * @dataProvider providerTestFormula()
   */
  public function testFormula(string $file): void {
    $dir = dirname(__DIR__) . '/fixtures/formula';
    if (!preg_match('@^(\w+)\.(\w+)\.php$@', $file, $m)) {
      self::fail("Unexpected file name '$file'.");
    }
    [, $base, $case] = $m;
    $formula = include "$dir/$base.php";
    if (!$formula instanceof FormulaInterface) {
      self::fail('Formula must implement FormulaInterface.');
    }
    $conf = Yaml::parseFile("$dir/$base.$case.yml");
    $incarnator = $this->getIncarnator();
    $generator = Generator::fromFormula(
      $formula,
      $incarnator);
    $this->doTestGenerator(
      $generator,
      $conf,
      "$dir/$base.$case.php",
      NULL);
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
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   *   (unexpected) Failure to create generator for interface formula.
   *
   * @dataProvider providerTestIface()
   */
  public function testIface(string $type, string $name): void {
    $interface = strtr(IntOpInterface::class, ['IntOp' => $type]);
    $filebase = dirname(__DIR__) . '/fixtures/iface/' . $type . '/' . $name;
    $conf = Yaml::parseFile($filebase . '.yml');
    $incarnator = $this->getIncarnator();
    $generator = Generator::fromIface(
      $interface,
      $incarnator);
    $this->doTestGenerator(
      $generator,
      $conf,
      $filebase . '.php',
      $interface);
  }

  /**
   * Data provider.
   *
   * @return \Iterator|array[]
   *   Argument combos.
   */
  public function providerTestIface(): \Iterator {
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

  /**
   * @param \Donquixote\Ock\Generator\GeneratorInterface $generator
   * @param mixed $conf
   * @param string $file_expected
   * @param string|null $interface
   */
  private function doTestGenerator(GeneratorInterface $generator, $conf, string $file_expected, ?string $interface): void {
    try {
      $php_raw = $generator->confGetPhp($conf);
    }
    catch (GeneratorException $e) {
      self::assertExceptionPhpFile($e, $file_expected);
      return;
    }
    self::assertValuePhpFile($php_raw, $interface, $file_expected);
  }

  /**
   * @param \Donquixote\Ock\Exception\GeneratorException $e
   * @param string $file_expected
   */
  private static function assertExceptionPhpFile(GeneratorException $e, string $file_expected): void {
    $class = get_class($e);
    $message_php = var_export($e->getMessage(), TRUE);
    $php_statement = <<<EOT
// Exception thrown in generator.
return static function () {
  throw new $class(
    $message_php);
};
EOT;
    $php_actual = CodegenUtil::formatAsFile($php_statement);
    TestUtil::assertFileContents($file_expected, $php_actual);
  }

  /**
   * @param string $expression
   *   PHP value expression.
   * @param string|null $interface
   *   Interface of the value.
   * @param string $file_expected
   *   Php file containing the expected php code.
   */
  private static function assertValuePhpFile(string $expression, ?string $interface, string $file_expected): void {
    $php_nice = CodegenUtil::autoIndent(
      $expression,
      '  ',
      $interface !== NULL ? '  ' : '');
    $php_statement = CodegenUtil::buildReturnStatement($php_nice);
    if ($interface !== NULL) {
      $php_statement = <<<EOT
return static function (): \\$interface {
  $php_statement
};
EOT;
    }
    $php_actual = CodegenUtil::formatAsFile($php_statement);
    TestUtil::assertFileContents($file_expected, $php_actual);
  }

}
