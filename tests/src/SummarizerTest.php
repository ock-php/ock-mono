<?php

namespace Donquixote\Ock\Tests;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Summarizer\Summarizer;
use Donquixote\Ock\Tests\Fixture\IntOp\IntOpInterface;
use Donquixote\Ock\Tests\Translator\Translator_Test;
use Donquixote\Ock\Translator\Translator_Passthru;
use Symfony\Component\Yaml\Yaml;

class SummarizerTest extends FormulaTestBase {

  /**
   * Tests various formulas.
   *
   * @param string $base
   * @param string $case
   *
   * @dataProvider providerTestFormula()
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public function testFormula(string $base, string $case): void {
    $dir = dirname(__DIR__) . '/fixtures/formula';
    $formula = include "$dir/$base.php";
    if (!$formula instanceof FormulaInterface) {
      self::fail('Formula must implement FormulaInterface.');
    }
    $conf = Yaml::parseFile("$dir/$base.$case.yml");
    $incarnator = $this->getIncarnator();

    $summarizer = Summarizer::fromFormula(
      $formula,
      $incarnator);
    $summary = $summarizer->confGetSummary($conf);
    self::assertNotNull($summary);

    self::assertSummaryEqualsFile(
      "$dir/$base.$case.html",
      $summary->convert(new Translator_Passthru()));

    if (file_exists($file = "$dir/$base.$case.t.html")) {
      self::assertSummaryEqualsFile(
        $file,
        $summary->convert(new Translator_Test()));
    }
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
    foreach ($candidates as $candidate) {
      if (preg_match('@^(\w+)\.(\w+)\.html$@', $candidate, $m)) {
        [, $base, $case] = $m;
        yield [$base, $case];
      }
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
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public function testIfaceSummarizer(string $type, string $name) {
    $interface = strtr(IntOpInterface::class, ['IntOp' => $type]);
    $filebase = dirname(__DIR__) . '/fixtures/iface/' . $type . '/' . $name;
    $conf = Yaml::parseFile($filebase . '.yml');
    $incarnator = $this->getIncarnator();

    $summarizer = Summarizer::fromIface(
      $interface,
      $incarnator);
    $summary = $summarizer->confGetSummary($conf);
    self::assertNotNull($summary);

    self::assertSummaryEqualsFile(
      "$filebase.html",
      $summary->convert(new Translator_Passthru()));

    if (file_exists($file = "$filebase.t.html")) {
      self::assertSummaryEqualsFile(
        $file,
        $summary->convert(new Translator_Test()));
    }
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
   * @throws \PHPUnit\Framework\ExpectationFailedException
   */
  public static function assertSummaryEqualsFile(string $file, string $summary_str) {
    // Remove trailing blank line.
    $expected = rtrim(file_get_contents($file));
    // Use additional wrapper div to cover pure text.
    self::assertXmlStringEqualsXmlString(
      self::normalizeSummary($expected),
      self::normalizeSummary($summary_str));
  }

  /**
   * Normalizes a summary string for comparison.
   *
   * @param string $summary
   *
   * @return string
   */
  public static function normalizeSummary(string $summary): string {
    // Remove trailing blank line.
    $summary = rtrim($summary);
    return "<div>\n$summary\n</div>";
  }

}
