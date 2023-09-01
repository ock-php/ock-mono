<?php

namespace Donquixote\ClassDiscovery\ClassFilesIA;

use Donquixote\ClassDiscovery\NamespaceDirectory;
use Donquixote\ClassDiscovery\NsDirUtil;

class ClassFilesIA {

  /**
   * @param string $dir
   * @param string $namespace
   *
   * @return ClassFilesIA_NamespaceDirectoryPsr4
   */
  public static function psr4($dir, $namespace): ClassFilesIAInterface {
    return new ClassFilesIA_NamespaceDirectoryPsr4(
      $dir,
      NsDirUtil::terminateNamespace($namespace),
    );
  }

  /**
   * @param string $dir
   * @param string $namespace
   * @param int $nLevelsUp
   *
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  public static function psr4Up($dir, $namespace, $nLevelsUp = 0): ClassFilesIAInterface {
    $nsDir = NamespaceDirectory::create($dir, $namespace)
      ->requireParentN($nLevelsUp);
    return ClassFilesIA_NamespaceDirectoryPsr4::createFromNsdirObject($nsDir);
  }

  /**
   * @param string $class
   * @param int $nLevelsUp
   *
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   *
   * @throws \ReflectionException
   *   Class does not exist.
   */
  public static function psr4FromClass($class, $nLevelsUp = 0): ClassFilesIAInterface {
    $nsDir = NamespaceDirectory::createFromClass($class)
      ->requireParentN($nLevelsUp);
    return ClassFilesIA_NamespaceDirectoryPsr4::createFromNsdirObject($nsDir);
  }

  /**
   * @param \Donquixote\ClassDiscovery\NamespaceDirectory $nsdir
   *
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  public static function psr4FromNsdirObject(NamespaceDirectory $nsdir): ClassFilesIAInterface {
    if (!is_dir($nsdir->getDirectory())) {
      return new ClassFilesIA_Empty();
    }
    return new ClassFilesIA_NamespaceDirectoryPsr4(
      $nsdir->getDirectory(),
      $nsdir->getTerminatedNamespace());
  }

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface[] $classFilesIAs
   *
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  public static function multiple(array $classFilesIAs): ClassFilesIAInterface {
    return new ClassFilesIA_Multiple($classFilesIAs);
  }

  /**
   * @param class-string[] $classes
   *
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   *
   * @throws \ReflectionException
   *   One of the classes does not exist.
   */
  public static function psr4FromClasses(array $classes): ClassFilesIAInterface {
    return self::multiple(array_map(
      [self::class, 'psr4FromClass'],
      $classes,
    ));
  }

}
