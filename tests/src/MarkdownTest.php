<?php

declare(strict_types = 1);

namespace Donquixote\DID\Tests;

use Donquixote\DID\Tests\Util\TestUtil;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class MarkdownTest extends TestCase {

  /**
   * @param string $dirname
   * @param string $testCaseFileName
   * @param string $markdownFileName
   *
   * @throws \Exception
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
    $this->includeFile($testCaseFile, [
      'php' => $parts[2],
      'first' => $parts[2],
    ]);
  }

  private function includeFile(): void {
    extract(func_get_arg(1));
    include func_get_arg(0);
  }

  /**
   * @param string $dirname
   * @param string $markdownFileName
   *
   * @throws \Exception
   *
   * @dataProvider provider
   */
  public function testSelfUpdating(string $dirname, string $markdownFileName): void {
    if (!preg_match('@^(fail.|)(.+)\.md$@', $markdownFileName, $m)) {
      Assert::fail('File name does not match.');
    }
    [, $fail, $name] = $m;
    $markdownFile = dirname(__DIR__) . '/fixtures/' . $dirname . '/' . $markdownFileName;
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
    $templateFile = dirname($markdownFile) . '/_template.md.php';
    if (!is_file($templateFile)) {
      throw new \Exception(sprintf(
        'Missing template file %s.',
        $templateFile,
      ));
    }
    $actualMarkdown = $this->includeTemplateFile($templateFile, [
      'title' => ucfirst(str_replace('-', ' ', basename($name, '.md'))),
      'php' => $parts[2],
      'first' => $parts[2],
      'types' => $types,
      'texts' => $texts,
      'snippets' => $snippets,
      'fail' => !!$fail,
    ]);
    TestUtil::assertFileContents(
      $markdownFile,
      $actualMarkdown,
    );
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
   * @return \Iterator
   */
  public function providerTestOther(): \Iterator {
    $fixturesDir = $this->getFixturesDir();
    foreach ($this->getDirNames() as $dirname) {
      $markdownFileNames = $this->getMarkdownFileNames($fixturesDir . '/' . $dirname);
      foreach ($this->getTestCaseFileNames($fixturesDir . '/' . $dirname) as $testCaseFileName) {
        foreach ($markdownFileNames as $markdownFileName) {
          if (str_starts_with($markdownFileName, 'fail.')) {
            continue;
          }
          yield [$dirname, $testCaseFileName, $markdownFileName];
        }
      }
    }
  }

  /**
   * @return \Iterator
   */
  public function provider(): \Iterator {
    $fixturesDir = $this->getFixturesDir();
    foreach ($this->getDirNames() as $dirname) {
      if (!is_file($fixturesDir . '/' . $dirname . '/_template.md.php')) {
        continue;
      }
      foreach ($this->getMarkdownFileNames($fixturesDir . '/' . $dirname) as $name) {
        yield [$dirname, $name];
      }
    }
  }

  /**
   * @param string $dir
   *
   * @return string[]
   */
  private function getTestCaseFileNames(string $dir): array {
    $names = [];
    foreach (scandir($dir) as $candidate) {
      if (preg_match('@^_[\w\-]+\.php$@', $candidate)) {
        $names[] = $candidate;
      }
    }
    return $names;
  }

  /**
   * @param string $dir
   *
   * @return string[]
   */
  private function getMarkdownFileNames(string $dir): array {
    $names = [];
    foreach (scandir($dir) as $candidate) {
      if (preg_match('@^.+\.md$@', $candidate)) {
        $names[] = $candidate;
      }
    }
    return $names;
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
