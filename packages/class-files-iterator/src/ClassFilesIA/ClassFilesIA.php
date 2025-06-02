<?php

namespace Ock\ClassFilesIterator\ClassFilesIA;

use Ock\ClassFilesIterator\NamespaceDirectory;
use Ock\ClassFilesIterator\NsDirUtil;

/**
 * Static factories for ClassFilesIAInterface objects.
 */
class ClassFilesIA {

  /**
   * @param string $directory
   * @param string $namespace
   *
   * @return \Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface
   */
  public static function psr4(string $directory, string $namespace): ClassFilesIAInterface {
    return new ClassFilesIA_Psr4(
      $directory,
      NsDirUtil::terminateNamespace($namespace),
    );
  }

  /**
   * @param string $directory
   * @param string $namespace
   * @param int $nLevelsUp
   *
   * @return \Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface
   */
  public static function psr4Up(string $directory, string $namespace, int $nLevelsUp = 0): ClassFilesIAInterface {
    return NamespaceDirectory::create($directory, $namespace)
      ->requireParentN($nLevelsUp);
  }

  /**
   * @param class-string $class
   * @param int $nLevelsUp
   *
   * @return \Ock\ClassFilesIterator\NamespaceDirectory
   */
  public static function psr4FromClass(string $class, int $nLevelsUp = 0): NamespaceDirectory {
    $result = NamespaceDirectory::fromClass($class);
    if ($nLevelsUp !== 0) {
      $result = $result->requireParentN($nLevelsUp);
    }
    return $result;
  }

  /**
   * @param \Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface[] $classFilesIAs
   *
   * @return \Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface
   */
  public static function concat(array $classFilesIAs): ClassFilesIAInterface {
    return new ClassFilesIA_Concat($classFilesIAs);
  }

  /**
   * @param class-string[] $classes
   *
   * @return \Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface
   */
  public static function psr4FromClasses(array $classes): ClassFilesIAInterface {
    return self::concat(array_map(
      self::psr4FromClass(...),
      $classes,
    ));
  }

}
