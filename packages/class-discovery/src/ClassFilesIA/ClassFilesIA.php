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
  public static function psr4(string $dir, string $namespace): ClassFilesIAInterface {
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
  public static function psr4Up(string $dir, string $namespace, int $nLevelsUp = 0): ClassFilesIAInterface {
    return NamespaceDirectory::create($dir, $namespace)
      ->requireParentN($nLevelsUp);
  }

  /**
   * @param string $class
   * @param int $nLevelsUp
   *
   * @return \Donquixote\ClassDiscovery\NamespaceDirectory
   *
   * @throws \ReflectionException
   *   Class does not exist.
   */
  public static function psr4FromClass(string $class, int $nLevelsUp = 0): NamespaceDirectory {
    $result = NamespaceDirectory::createFromClass($class);
    if ($nLevelsUp !== 0) {
      $result = $result->requireParentN($nLevelsUp);
    }
    return $result;
  }

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface[] $classFilesIAs
   *
   * @return \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  public static function multiple(array $classFilesIAs): ClassFilesIAInterface {
    return new ClassFilesIA_Concat($classFilesIAs);
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
      self::psr4FromClass(...),
      $classes,
    ));
  }

}
