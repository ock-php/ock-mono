<?php

namespace Donquixote\Ock\Tests;

use Donquixote\Ock\Tests\Translator\Translator_Test;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\Translator\Translator_Passthru;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase {

  /**
   * Tests various formulas.
   *
   * @param string $name
   *   File containing the expected generated code.
   *
   * @dataProvider providerText()
   */
  public function testText(string $name): void {
    $dir = dirname(__DIR__) . '/fixtures/text';
    $text = include "$dir/$name.php";
    if (!$text instanceof TextInterface) {
      self::fail('Text must implement TextInterface.');
    }

    self::assertTextEqualsFile(
      "$dir/$name.html",
      $text->convert(new Translator_Passthru()));

    if (file_exists($file = "$dir/$name.t.html")) {
      self::assertTextEqualsFile(
        $file,
        $text->convert(new Translator_Test()));
    }
  }

  /**
   * Data provider.
   *
   * @return \Iterator
   *   Parameter combos.
   */
  public function providerText(): \Iterator {
    $dir = dirname(__DIR__) . '/fixtures/text';
    $by_ext = ['html' => [], 'php' => []];
    $by_name = [];
    foreach (scandir($dir) as $candidate) {
      if (preg_match('@^(\w+)\.(html|php)$@', $candidate, $m)) {
        [, $name, $ext] = $m;
        $by_ext[$ext][$name] = $name;
        $by_name[$name][$ext] = $candidate;
      }
    }
    $complete_names = array_intersect_key(...array_values($by_ext));
    $orphan_candidates = array_diff_key($by_name, $complete_names);
    if ($orphan_candidates) {
      self::fail('Found orphan files: ' . implode(
        ', ',
        array_replace(...array_values($orphan_candidates))));
    }
    foreach ($complete_names as $name) {
      yield [$name];
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
  public static function assertTextEqualsFile(string $file, string $summary_str) {
    // Remove trailing blank line.
    $expected = rtrim(file_get_contents($file));
    // Use additional wrapper div to cover pure text.
    self::assertXmlStringEqualsXmlString(
      self::normalizeText($expected),
      self::normalizeText($summary_str));
  }

  /**
   * @param string $summary
   *
   * @return string
   */
  public static function normalizeText(string $summary): string {
    // Remove trailing blank line.
    $summary = rtrim($summary);
    return "<div>\n$summary\n</div>";
  }

}
