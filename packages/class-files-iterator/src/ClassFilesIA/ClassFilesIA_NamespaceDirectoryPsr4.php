<?php

namespace Ock\ClassFilesIterator\ClassFilesIA;

use Ock\ClassFilesIterator\NamespaceDirectory;
use Ock\ClassFilesIterator\NsDirUtil;

class ClassFilesIA_NamespaceDirectoryPsr4 implements ClassFilesIAInterface {

  /**
   * @param string $directory
   * @param string $terminatedNamespace
   */
  public function __construct(
    private readonly string $directory,
    private readonly string $terminatedNamespace,
  ) {}

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
   * @return \Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface
   * @throws \ReflectionException
   */
  public static function createFromClass(string $class, int $nLevelsUp = 0): ClassFilesIAInterface {
    $nsDir = NamespaceDirectory::createFromClass($class)
      ->requireParentN($nLevelsUp);
    return self::createFromNsdirObject($nsDir);
  }

  /**
   * @param \Ock\ClassFilesIterator\NamespaceDirectory $nsdir
   *
   * @return \Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface
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

  #[\Override]
  public function getIterator(): \Iterator {
    return NsDirUtil::iterate($this->directory, $this->terminatedNamespace);
  }

}
