<?php

declare(strict_types = 1);

namespace Donquixote\DID\Tests\Util;

use Donquixote\DID\Evaluator\Evaluator;
use Donquixote\DID\Evaluator\EvaluatorInterface;
use Donquixote\DID\Tests\Fixtures\GenericObject;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Container\ContainerInterface;

class TestUtil {

  /**
   * Checks an env flag that determines if fixture files should be updated.
   *
   * @return bool
   *   If TRUE, fixture files will be overwritten.
   */
  public static function updateTestsEnabled(): bool {
    return (bool) getenv('UPDATE_TESTS');
  }

  /**
   * @param string $file
   *   File with expected content.
   * @param string $content_actual
   *   Actual content.
   */
  public static function assertFileContents(string $file, string $content_actual): void {
    try {
      if (!is_file($file)) {
        Assert::fail("File '$file' is missing.");
      }
      $content_expected = file_get_contents($file);
      Assert::assertSame($content_expected, $content_actual);
    }
    catch (AssertionFailedError $e) {
      if (self::updateTestsEnabled()) {
        file_put_contents($file, $content_actual);
      }
      throw $e;
    }
  }

  public static function createDummyEvaluator(): EvaluatorInterface {
    $container = new DummyContainer();
    return new Evaluator($container);
  }

  public static function formatMarkdownSection(string $label, string $snippet, string $language = 'php'): string {
    return "$label:\n\n```$language\n$snippet\n```\n";
  }

}
