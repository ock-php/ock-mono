<?php

declare(strict_types=1);

namespace Donquixote\Ock\Tests\Util;

use PHPUnit\Framework\Assert;

class TestUtil {

  /**
   * @param string $file
   *   File with expected content.
   * @param string $content_actual
   *   Actual content.
   */
  public static function assertFileContents(string $file, string $content_actual): void {
    $content_expected = file_get_contents($file);
    Assert::assertSame($content_expected, $content_actual);
  }

  /**
   * @param string $file
   * @param string $xml_actual
   *
   * @throws \PHPUnit\Util\Xml\Exception
   * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
   * @throws \PHPUnit\Framework\ExpectationFailedException
   */
  public static function assertXmlFileContents(string $file, string $xml_actual) {
    $xml_actual = rtrim($xml_actual) . "\n";
    $xml_expected = file_get_contents($file);
    // Use additional wrapper div to cover pure text.
    Assert::assertXmlStringEqualsXmlString(
      self::normalizeXml($xml_expected),
      self::normalizeXml($xml_actual));
  }

  /**
   * Prepares an xml fragment for assertXmlStringEqualsXmlString().
   *
   * @param string $xml
   *   XML fragment that might lack wrapper tags.
   *
   * @return string
   *   XML fragment with guaranteed wrapper tags.
   */
  public static function normalizeXml(string $xml): string {
    return "<div>\n$xml\n</div>";
  }

}
