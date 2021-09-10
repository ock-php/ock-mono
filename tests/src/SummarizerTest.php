<?php

namespace Donquixote\ObCK\Tests;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Summarizer\Summarizer;
use Donquixote\ObCK\Tests\Fixture\IntOp\IntOpInterface;
use Donquixote\ObCK\Translator\Translator;
use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\Yaml\Yaml;

class SummarizerTest extends FormulaTestBase {

  /**
   * Tests various formulas.
   *
   * @param string $file
   *   File containing the expected generated code.
   *
   * @dataProvider providerTestFormula()
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public function testFormula(string $file): void {
    $dir = dirname(__DIR__) . '/fixtures/formula';
    if (!preg_match('@^(\w+)\.(\w+)\.html$@', $file, $m)) {
      self::fail("Unexpected file name '$file'.");
    }
    list(, $base, $case) = $m;
    $formula = include "$dir/$base.php";
    if (!$formula instanceof FormulaInterface) {
      self::fail('Formula must implement FormulaInterface.');
    }
    $conf = Yaml::parseFile("$dir/$base.$case.yml");
    $formula_to_anything = $this->getFormulaToAnything();

    $summarizer = Summarizer::fromFormula(
      $formula,
      $formula_to_anything);
    $translator = Translator::createPassthru();
    $summary = $summarizer->confGetSummary($conf);
    self::assertNotNull($summary);
    $summary_str = $summary->convert($translator);
    self::assertSummaryEqualsFile("$dir/$file", $summary_str);
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
    $candidates = preg_grep('@^(\w+)\.(\w+)\.html$@', $candidates);
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
   * @dataProvider providerTestIfaceSummarizer()
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public function testIfaceSummarizer(string $type, string $name) {
    $interface = strtr(IntOpInterface::class, ['IntOp' => $type]);
    $filebase = dirname(__DIR__) . '/fixtures/iface/' . $type . '/' . $name;
    $conf = Yaml::parseFile($filebase . '.yml');
    $formula_to_anything = $this->getFormulaToAnything();

    $summarizer = Summarizer::fromIface(
      $interface,
      $formula_to_anything);
    $translator = Translator::createPassthru();
    $summary = $summarizer->confGetSummary($conf);
    self::assertNotNull($summary);
    $summary_str = $summary->convert($translator);
    self::assertSummaryEqualsFile("$filebase.html", $summary_str);
  }

  /**
   * Data provider.
   *
   * @return \Iterator|array[]
   *   Argument combos.
   */
  public function providerTestIfaceSummarizer(): \Iterator {
    $dir = dirname(__DIR__) . '/fixtures/iface';
    foreach (scandir($dir) as $dir_candidate) {
      if ($dir_candidate === '.' || $dir_candidate === '..') {
        continue;
      }
      foreach (scandir($dir . '/' . $dir_candidate) as $file_candidate) {
        if (preg_match('@^(\w+)\.html$@', $file_candidate, $m)) {
          yield [$dir_candidate, $m[1]];
        }
      }
    }
  }

  /**
   * @param string $file
   * @param string $summary_str
   *
   * @throws \PHPUnit\Util\Xml\Exception
   * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
   * @throws ExpectationFailedException
   */
  public static function assertSummaryEqualsFile(string $file, string $summary_str) {
    // Remove trailing blank line.
    $expected = rtrim(file_get_contents($file));
    // Use additional wrapper div to cover pure text.
    self::assertXmlStringEqualsXmlString(
      self::normalizeSummary($expected),
      self::normalizeSummary($summary_str));
  }

  public static function normalizeSummary(string $summary): string {
    // Remove trailing blank line.
    $summary = rtrim($summary);
    return "<div>\n$summary\n</div>";
  }

}
