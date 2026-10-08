<?php

declare(strict_types=1);

use Ock\Text\Tests\Translator_Testing;
use Ock\Text\Tests\Util\XmlTestUtil;
use Ock\Text\TextInterface;
use Ock\Text\Translator\Translator_Passthru;
use PHPUnit\Framework\TestCase;
use function Ock\Helpers\scandir_known;

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
    $dir = dirname(__DIR__) . '/fixtures';
    $text = include "$dir/$name.php";
    if (!$text instanceof TextInterface) {
      self::fail('Text must implement TextInterface.');
    }

    XmlTestUtil::assertXmlFileContents(
      "$dir/$name.html",
      $text->convert(new Translator_Passthru()));

    if (file_exists($file = "$dir/$name.t.html")) {
      XmlTestUtil::assertXmlFileContents(
        $file,
        $text->convert(new Translator_Testing()));
    }
  }

  /**
   * Data provider.
   *
   * @return \Iterator
   *   Parameter combos.
   */
  public static function providerText(): \Iterator {
    $dir = dirname(__DIR__) . '/fixtures';
    $names_map = [];
    foreach (scandir_known($dir) as $candidate) {
      if (preg_match('@^(\w+)\.\w+$@', $candidate, $m)) {
        $names_map[$m[1]] = TRUE;
      }
    }
    foreach ($names_map as $name => $_) {
      yield [$name];
    }
  }

}
