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
    $contents = DirectoryContents::load($dir);
    foreach ($contents->iterateClassAndNamespaceMap() as $name => $is_namespace) {
      if (!$is_namespace) {
        // @phpstan-ignore generator.valueType
        yield $dir . '/' . $name . '.php' => $terminatedNamespace . $name;
      }
      else {
        yield from self::doIterateRecursively(
          $dir . '/' . $name,
          $terminatedNamespace . $name . '\\',
        );
      }
    }
  }

}
