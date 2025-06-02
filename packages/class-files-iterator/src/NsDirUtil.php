<?php

namespace Ock\ClassFilesIterator;

class NsDirUtil {

  /**
   * See http://php.net/manual/en/language.oop5.basic.php
   */
  const CLASS_NAME_REGEX = /** @lang RegExp */ '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';

  /**
   * @param string $namespace
   *
   * @return string
   *
   * @throws \InvalidArgumentException
   */
  public static function terminateNamespace(string $namespace): string {
    if ('' === $namespace) {
      return '';
    }
    if (str_ends_with($namespace, '\\')) {
      throw new \InvalidArgumentException('Namespace must be provided without trailing backslash.');
    }
    if ('\\' === $namespace[0]) {
      throw new \InvalidArgumentException('Namespace must be provided without leading backslash.');
    }
    return $namespace . '\\';
  }

  /**
   * Recursively iterates over class files.
   *
   * @param string $dir
   *   Directory without trailing slash.
   * @param string $terminatedNamespace
   *   Namespace with trailing separator.
   *
   * @return \Iterator<string, class-string>
   *   Format: $[$file] = $class
   */
  public static function iterate(string $dir, string $terminatedNamespace): \Iterator {
    assert(!str_ends_with($dir, '/'));
    assert(str_ends_with($terminatedNamespace, '\\') || $terminatedNamespace === '');
    self::assertReadableDirectory($dir);
    return self::doIterateRecursively($dir, $terminatedNamespace);
  }

  /**
   * Asserts that a path is a readable directory.
   *
   * @param string $dir
   *   Directory path.
   */
  public static function assertReadableDirectory(string $dir): void {
    if (!is_dir($dir)) {
      if (!file_exists($dir)) {
        throw new \RuntimeException("Directory '$dir' does not exist.");
      }
      throw new \RuntimeException("Path '$dir' is not a directory.");
    }
    if (!is_readable($dir)) {
      throw new \RuntimeException("Directory '$dir' is not readable.");
    }
  }

  /**
   * Recursively iterates over class files.
   *
   * @param string $dir
   *   Directory without trailing slash.
   * @param string $terminatedNamespace
   *   Namespace with trailing separator.
   *
   * @return \Iterator<string, class-string>
   *   Format: $[$file] = $class
   */
  private static function doIterateRecursively(string $dir, string $terminatedNamespace): \Iterator {
    foreach (self::scanKnownDir($dir) as $candidate) {
      if ('.' === $candidate[0]) {
        continue;
      }
      $path = $dir . '/' . $candidate;
      if (\str_ends_with($candidate, '.php')) {
        if (!is_file($path)) {
          continue;
        }
        $name = substr($candidate, 0, -4);
        if (!preg_match(self::CLASS_NAME_REGEX, $name)) {
          continue;
        }
        // @phpstan-ignore generator.valueType
        yield $path => $terminatedNamespace . $name;
      }
      else {
        if (!is_dir($path)) {
          continue;  // @codeCoverageIgnore
        }
        if (!preg_match(self::CLASS_NAME_REGEX, $candidate)) {
          continue;
        }
        yield from self::doIterateRecursively(
          $path,
          $terminatedNamespace . $candidate . '\\',
        );
      }
    }
  }

  /**
   * Runs scandir() on a known directory.
   *
   * Throws a runtime exception on failure, instead of returning false.
   * This is considered an unhandled exception, because it is assumed that the
   * given directory always exists.
   *
   * @param string $dir
   *   Known directory to scan.
   *
   * @return list<string>
   *   Items in the directory in alphabetic order.
   *   This also includes '.' and '..'.
   *   Calling code already does filtering with regex or other means, so it
   *   would be redundant to do additional filtering here.
   */
  public static function scanKnownDir(string $dir): array {
    $candidates = @\scandir($dir, \SCANDIR_SORT_ASCENDING);
    if ($candidates === false) {
      throw new \RuntimeException("Failed to scandir('$dir').");
    }
    return $candidates;
  }

}
