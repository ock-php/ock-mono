<?php

namespace Donquixote\ClassDiscovery\ClassFilesIA;

use Donquixote\ClassDiscovery\NamespaceDirectory;
use Donquixote\ClassDiscovery\NsDirUtil;

class ClassFilesIA_NamespaceDirectoryPsr4 implements ClassFilesIAInterface {

  /**
   * See http://php.net/manual/en/language.oop5.basic.php
   */
  const CLASS_NAME_REGEX = /** @lang RegExp */ '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';

  /**
   * @param string $dir
   * @param string $namespace
   *
   * @return self
   */
  public static function create(string $dir, string $namespace): self {
    return new self(
      $dir,
      NsDirUtil::terminateNamespace($namespace),
    );
  }

  /**
   * @param class-string $class
   * @param int $nLevelsUp
   *
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   * @throws \ReflectionException
   */
  public static function createFromClass(string $class, int $nLevelsUp = 0): ClassFilesIAInterface {
    $nsDir = NamespaceDirectory::createFromClass($class)
      ->requireParentN($nLevelsUp);
    return self::createFromNsdirObject($nsDir);
  }

  /**
   * @param \Donquixote\ClassDiscovery\NamespaceDirectory $nsdir
   *
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  public static function createFromNsdirObject(NamespaceDirectory $nsdir): ClassFilesIAInterface {
    if (!is_dir($nsdir->getDirectory())) {
      return new ClassFilesIA_Empty();
    }
    return new self(
      $nsdir->getDirectory(),
      $nsdir->getTerminatedNamespace(),
    );
  }

  /**
   * @param string $directory
   * @param string $terminatedNamespace
   */
  public function __construct(
    private string $directory,
    private readonly string $terminatedNamespace,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function withRealpathRoot(): static {
    $clone = clone $this;
    $clone->directory = realpath($this->directory);
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    return self::scan($this->directory, $this->terminatedNamespace);
  }

  /**
   * @param string $dir
   * @param string $terminatedNamespace
   *
   * @return \Iterator<string, class-string>
   *   Format: $[$file] = $class
   */
  private static function scan(string $dir, string $terminatedNamespace): \Iterator {
    foreach (\scandir($dir, \SCANDIR_SORT_ASCENDING) as $candidate) {
      if ('.' === $candidate[0]) {
        continue;
      }
      $path = $dir . '/' . $candidate;
      if (str_ends_with($candidate, '.php')) {
        if (!is_file($path)) {
          continue;
        }
        $name = substr($candidate, 0, -4);
        if (!preg_match(self::CLASS_NAME_REGEX, $name)) {
          continue;
        }
        yield $path => $terminatedNamespace . $name;
      }
      else {
        if (!is_dir($path)) {
          continue;
        }
        if (!preg_match(self::CLASS_NAME_REGEX, $candidate)) {
          continue;
        }
        // @todo Make PHP 7 version with "yield from".
        yield from self::scan($path, $terminatedNamespace . $candidate . '\\');
      }
    }
  }
}
