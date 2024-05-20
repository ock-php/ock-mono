<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\ReflectionClassesIA;

use Ock\ClassDiscovery\ClassFilesIA\ClassFilesIA;
use Ock\ClassDiscovery\ClassNamesIA\ClassNamesIA_Array;

/**
 * Static factories for ReflectionClassesIAInterface objects.
 */
class ReflectionClassesIA {

  /**
   * @param class-string[] $classes
   *
   * @return \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface
   */
  public static function fromClassNames(array $classes): ReflectionClassesIAInterface {
    $classNamesIA = new ClassNamesIA_Array($classes);
    return new ReflectionClassesIA_ClassNamesIA($classNamesIA);
  }

  /**
   * @param string $dir
   * @param string $namespace
   *
   * @return \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface
   */
  public static function psr4(string $dir, string $namespace): ReflectionClassesIAInterface {
    $classFilesIA = ClassFilesIA::psr4($dir, $namespace);
    return new ReflectionClassesIA_ClassFilesIA($classFilesIA);
  }

  /**
   * @param string $dir
   * @param string $namespace
   * @param int $nLevelsUp
   *
   * @return \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface
   */
  public static function psr4Up(string $dir, string $namespace, int $nLevelsUp = 0): ReflectionClassesIAInterface {
    $classFilesIA = ClassFilesIA::psr4Up($dir, $namespace, $nLevelsUp);
    return new ReflectionClassesIA_ClassFilesIA($classFilesIA);
  }

  /**
   * @param string $class
   * @param int $nLevelsUp
   *
   * @return \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface
   *
   * @throws \ReflectionException
   *   Class does not exist.
   */
  public static function psr4FromClass(string $class, int $nLevelsUp = 0): ReflectionClassesIAInterface {
    $classFilesIA = ClassFilesIA::psr4FromClass($class, $nLevelsUp);
    return new ReflectionClassesIA_ClassFilesIA($classFilesIA);
  }

  /**
   * @param string $class
   * @param int $nLevelsUp
   *
   * @return \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface
   */
  public static function psr4FromKnownClass(string $class, int $nLevelsUp = 0): ReflectionClassesIAInterface {
    $classFilesIA = ClassFilesIA::psr4FromKnownClass($class, $nLevelsUp);
    return new ReflectionClassesIA_ClassFilesIA($classFilesIA);
  }

  /**
   * @param \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface[] $parts
   *
   * @return \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface
   */
  public static function concat(array $parts): ReflectionClassesIAInterface {
    return new ReflectionClassesIA_Concat($parts);
  }

  /**
   * @param class-string[] $classes
   *
   * @return \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface
   */
  public static function psr4FromClasses(array $classes): ReflectionClassesIAInterface {
    return self::concat(array_map(
      self::psr4FromClass(...),
      $classes,
    ));
  }

}
