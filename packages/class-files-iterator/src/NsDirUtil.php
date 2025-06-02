<?php

namespace Ock\ClassFilesIterator;

class NsDirUtil {

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
   * @param string $directory
   *   Directory without trailing slash.
   * @param string $terminatedNamespace
   *   Namespace with trailing separator.
   *
   * @return \Iterator<string, class-string>
   *   Format: $[$file] = $class
   */
  public static function iterate(string $directory, string $terminatedNamespace): \Iterator {
    assert(!str_ends_with($directory, '/'));
    assert(str_ends_with($terminatedNamespace, '\\') || $terminatedNamespace === '');
    self::assertReadableDirectory($directory);
    return self::doIterateRecursively($directory, $terminatedNamespace);
  }

  /**
   * Asserts that a path is a readable directory.
   *
   * @param string $directory
   *   Directory path.
   */
  public static function assertReadableDirectory(string $directory): void {
    if (!is_dir($directory)) {
      if (!file_exists($directory)) {
        throw new \RuntimeException("Directory '$directory' does not exist.");
      }
      throw new \RuntimeException("Path '$directory' is not a directory.");
    }
    if (!is_readable($directory)) {
      throw new \RuntimeException("Directory '$directory' is not readable.");
    }
  }

  /**
   * Recursively iterates over class files.
   *
   * @param string $directory
   *   Directory without trailing slash.
   * @param string $terminatedNamespace
   *   Namespace with trailing separator.
   *
   * @return \Iterator<string, class-string>
   *   Format: $[$file] = $class
   */
  private static function doIterateRecursively(string $directory, string $terminatedNamespace): \Iterator {
    $contents = DirectoryContents::load($directory);
    foreach ($contents->iterateClassAndNamespaceMap() as $name => $is_namespace) {
      if (!$is_namespace) {
        // @phpstan-ignore generator.valueType
        yield $directory . '/' . $name . '.php' => $terminatedNamespace . $name;
      }
      else {
        yield from self::doIterateRecursively(
          $directory . '/' . $name,
          $terminatedNamespace . $name . '\\',
        );
      }
    }
  }

}
