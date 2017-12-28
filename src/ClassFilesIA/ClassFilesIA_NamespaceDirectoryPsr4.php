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
   * @var string
   */
  private $directory;

  /**
   * @var string
   */
  private $terminatedNamespace;

  /**
   * @param string $dir
   * @param string $namespace
   *
   * @return self
   */
  public static function create($dir, $namespace) {

    return new self(
      $dir,
      NsDirUtil::terminateNamespace($namespace));
  }

  /**
   * @param string $dir
   * @param string $namespace
   * @param int $nLevelsUp
   *
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  public static function createN($dir, $namespace, $nLevelsUp = 0) {

    $nsDir = NamespaceDirectory::create($dir, $namespace)
      ->requireParentN($nLevelsUp);

    return self::createFromNsdirObject($nsDir);
  }

  /**
   * @param string $class
   * @param int $nLevelsUp
   *
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  public static function createFromClass($class, $nLevelsUp = 0) {

    $nsDir = NamespaceDirectory::createFromClass($class)
      ->requireParentN($nLevelsUp);

    return self::createFromNsdirObject($nsDir);
  }

  /**
   * @param \Donquixote\ClassDiscovery\NamespaceDirectory $nsdir
   *
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  public static function createFromNsdirObject(NamespaceDirectory $nsdir) {

    if (!is_dir($nsdir->getDirectory())) {
      return new ClassFilesIA_Empty();
    }

    return new self(
      $nsdir->getDirectory(),
      $nsdir->getTerminatedNamespace());
  }

  /**
   * @param string $directory
   * @param string $terminatedNamespace
   */
  public function __construct($directory, $terminatedNamespace) {
    $this->directory = $directory;
    $this->terminatedNamespace = $terminatedNamespace;
  }

  /**
   * Gets a version where all base paths are sent through ->realpath().
   *
   * This is useful when comparing the path to \ReflectionClass::getFileName().
   * Note that this does NOT send all discovered file paths through realpath(),
   * only the base path. E.g. if there is a symlink in a subfolder somewhere,
   * the resulting paths will not match up with \ReflectionClass::getFileName().
   *
   * @return static
   */
  public function withRealpathRoot() {
    $clone = clone $this;
    $clone->directory = realpath($this->directory);
    return $clone;
  }

  /**
   * @return \Traversable|string[]
   *   Format: $[$file] = $class
   */
  public function getIterator() {
    return self::scan($this->directory, $this->terminatedNamespace);
  }

  /**
   * @param string $dir
   * @param string $terminatedNamespace
   *
   * @return \Traversable|string[]
   *   Format: $[$file] = $class
   */
  private static function scan($dir, $terminatedNamespace) {

    foreach (scandir($dir) as $candidate) {

      if ('.' === $candidate[0]) {
        continue;
      }

      $path = $dir . '/' . $candidate;

      if ('.php' === substr($candidate, -4)) {

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
        foreach (self::scan(
          $path,
          $terminatedNamespace . $candidate . '\\') as $file => $class) {

          yield $file => $class;
        }
      }
      
    }
  }
}
