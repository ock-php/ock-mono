<?php

/**
 * @noinspection PhpDocMissingThrowsInspection
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types = 1);

namespace Donquixote\CodegenTools\Tests;

use Donquixote\CodegenTools\Tests\Util\TestUtil;
use Donquixote\CodegenTools\Util\CodeFormatUtil;
use Donquixote\CodegenTools\Util\CodeGen;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

class MarkdownTest extends TestCase {

  /**
   * @param string $dirname
   * @param string $markdownFileName
   *
   * @throws \Exception
   *
   * @dataProvider providerTestSelfUpdating
   */
  public function testSelfUpdating(string $dirname, string $markdownFileName): void {
    $snippetFileNames = $this->getSnippetFileNames($dirname);
    if (!preg_match('@^(0\.|)(.+)\.md$@', $markdownFileName, $m)) {
      Assert::fail('File name does not match.');
    }
    [, $fail, $name] = $m;
    $dir = $this->getFixturesDir() . '/' . $dirname;
    $markdownFile = $dir . '/' . $markdownFileName;
    $originalMarkdown = file_get_contents($markdownFile);
    $parts = preg_split(
      '@^```(\w*)\n(.*?)\n```$@sm',
      $originalMarkdown,
      -1,
      PREG_SPLIT_DELIM_CAPTURE,
    );
    $partss = [];
    foreach ($parts as $i => $part) {
      $partss[$i % 3][] = $part;
    }
    [$texts, $types, $snippets] = $partss;
    if (!isset($parts[2])) {
      throw new \Exception(sprintf('No original code found in %s.', $markdownFile));
    }
    $actualMarkdown = '';
    $hasFailingSnippets = false;
    foreach ($snippetFileNames as $snippetFileName) {
      $snippetFile = $dir . '/' . $snippetFileName;
      if (!is_file($snippetFile)) {
        throw new \Exception(sprintf(
          'Missing template file %s.',
          $snippetFile,
        ));
      }
      try {
        $actualMarkdown .= $this->includeTemplateFile($snippetFile, [
          'title' => ucfirst(str_replace('-', ' ', basename($name, '.md'))),
          'php' => $parts[2],
          'first' => $parts[2],
          'types' => $types,
          'texts' => $texts,
          'snippets' => $snippets,
          'fail' => !!$fail,
        ]);
      }
      catch (AssertionFailedError $e) {
        throw $e;
      }
      catch (\Throwable $e) {
        if (!$fail) {
          throw $e;
        }
        $hasFailingSnippets = true;
        $expression = CodeGen::phpConstruct(get_class($e), [
          var_export($this->sanitizeExceptionMessage($e->getMessage()), TRUE),
        ]);
        $snippet = CodeFormatUtil::formatAsSnippet('throw ' . $expression . ';');
        $actualMarkdown .= <<<EOT

Exception:

```php
$snippet
```

EOT;
      }
    }
    if ($fail && !$hasFailingSnippets) {
      self::fail('Expected at least one snippet to fail.');
    }
    TestUtil::assertFileContents(
      $markdownFile,
      $actualMarkdown,
    );
  }

  private function sanitizeExceptionMessage(string $message): string {
    $message = strtr($message, [
      realpath(dirname(__DIR__, 2)) => '<project root>',
      dirname(__DIR__, 2) => '<project root>',
    ]);
    $message = preg_replace('@, called in .* eval\(\)\'d code on line (\d+)$@', ', called in [..] eval()\'d code on line $1', $message);
    return $message;
  }

  /**
   * @return \Iterator
   */
  public function providerTestSelfUpdating(): \Iterator {
    foreach ($this->getDirNames() as $dirname) {
      $markdownFileNames = $this->getMarkdownFileNames($dirname);
      $snippetFileNames = $this->getSnippetFileNames($dirname);
      if (!$snippetFileNames) {
        continue;
      }
      foreach ($markdownFileNames as $markdownFileName) {
        yield $dirname . '/' . $markdownFileName => [$dirname, $markdownFileName];
      }
    }
  }

  /**
   * @param string $dirname
   * @param string $testCaseFileName
   * @param string $markdownFileName
   *
   * @dataProvider providerTestOther
   */
  public function testOther(string $dirname, string $testCaseFileName, string $markdownFileName): void {
    $dir = $this->getFixturesDir() . '/' . $dirname;
    $markdownFile = $dir . '/' . $markdownFileName;
    $originalMarkdown = file_get_contents($markdownFile);
    $parts = preg_split(
      '@^```(\w*)\n(.*?)\n```$@sm',
      $originalMarkdown,
      -1,
      PREG_SPLIT_DELIM_CAPTURE,
    );
    if (!isset($parts[2])) {
      throw new \Exception(sprintf('No original code found in %s.', $markdownFile));
    }
    $testCaseFile = $dir . '/' . $testCaseFileName;
    if (!is_file($testCaseFile)) {
      throw new \Exception(sprintf(
        'Missing template file %s.',
        $testCaseFile,
      ));
    }
    $partss = [];
    foreach ($parts as $i => $part) {
      $partss[$i % 3][] = $part;
    }
    [$texts, $types, $snippets] = $partss;
    $this->includeFile($testCaseFile, [
      'php' => $parts[2],
      'first' => $parts[2],
      'title' => ucfirst(str_replace('-', ' ', basename($markdownFileName, '.md'))),
      'types' => $types,
      'texts' => $texts,
      'snippets' => $snippets,
      'name' => $markdownFileName,
    ]);
  }

  /**
   * @return \Iterator
   */
  public function providerTestOther(): \Iterator {
    foreach ($this->getDirNames() as $dirname) {
      $markdownFileNames = $this->getMarkdownFileNames($dirname);
      foreach ($this->getMatchingFileNames($dirname, '@^_[\w\-]+\.php$@') as $testCaseFileName) {
        foreach ($markdownFileNames as $markdownFileName) {
          if (str_starts_with($markdownFileName, '0.')) {
            continue;
          }
          yield $dirname . '/' . $testCaseFileName . ': ' . $markdownFileName => [$dirname, $testCaseFileName, $markdownFileName];
        }
      }
    }
  }

  /**
   * @return string
   */
  private function includeTemplateFile(): string {
    extract(func_get_arg(1));
    ob_start();
    try {
      include func_get_arg(0);
      return ob_get_contents();
    }
    finally {
      ob_end_clean();
    }
  }

  /**
   * @noinspection PhpExceptionImmediatelyRethrownInspection
   */
  private function includeFile(): void {
    extract(func_get_arg(1));
    try {
      include func_get_arg(0);
    }
    catch (\Throwable $e) {
      throw $e;
    }
  }

  /**
   * @param string $dirname
   *
   * @return string[]
   */
  private function getSnippetFileNames(string $dirname): array {
    return $this->getMatchingFileNames($dirname, '@^_snippet\..+\.md\.php$@');
  }

  /**
   * @param string $dirname
   * @param string $pattern
   *
   * @return string[]
   */
  private function getMatchingFileNames(string $dirname, string $pattern): array {
    $names = [];
    foreach (scandir($this->getFixturesDir() . '/' . $dirname) as $candidate) {
      if (preg_match($pattern, $candidate)) {
        $names[] = $candidate;
      }
    }
    sort($names);
    return $names;
  }

  /**
   * @param string $dirname
   *
   * @return string[]
   */
  private function getMarkdownFileNames(string $dirname): array {
    return $this->getMatchingFileNames($dirname, '@^[^_\.].*\.md$@');
  }

  /**
   * @return string[]
   */
  private function getDirNames(): array {
    $fixturesDir = $this->getFixturesDir();
    $names = [];
    foreach (scandir($this->getFixturesDir()) as $candidate) {
      if (($candidate[0] ?? NULL) === '.') {
        continue;
      }
      if (!is_dir($fixturesDir . '/' . $candidate)) {
        continue;
      }
      $names[] = $candidate;
    }
    return $names;
  }

  private function getFixturesDir(): string {
    return dirname(__DIR__) . '/fixtures';
  }

}
