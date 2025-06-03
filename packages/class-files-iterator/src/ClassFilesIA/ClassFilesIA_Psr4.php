<?php

namespace Ock\ClassFilesIterator\ClassFilesIA;

use Ock\ClassFilesIterator\NamespaceDirectory;
use Ock\ClassFilesIterator\NsDirUtil;

class ClassFilesIA_Psr4 implements ClassFilesIAInterface {

  /**
   * @param string $directory
   * @param string $terminatedNamespace
   */
  public function __construct(
    private readonly string $directory,
    private readonly string $terminatedNamespace,
  ) {}

  /**
   * @param string $directory
   * @param string $namespace
   *
   * @return self
   */
  public static function create(string $directory, string $namespace): self {
    return new self(
      $directory,
      NsDirUtil::terminateNamespace($namespace),
    );
  }

  /**
   * @param class-string $class
   * @param int $nLevelsUp
   *
   * @return \Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface
   */
  public static function fromClass(string $class, int $nLevelsUp = 0): ClassFilesIAInterface {
    $nsDir = NamespaceDirectory::fromClass($class)
      ->requireParentN($nLevelsUp);
    return self::fromNsdirObject($nsDir);
  }

  /**
   * @param \Ock\ClassFilesIterator\NamespaceDirectory $nsdir
   *
   * @return \Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface
   */
  public static function fromNsdirObject(NamespaceDirectory $nsdir): ClassFilesIAInterface {
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
